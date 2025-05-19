<?php

namespace App\Filament\Resources;

use Filament\Forms;
use Filament\Tables;
use App\Models\Ticket;
use App\Models\Product;
use App\Models\PreOrder;
use Filament\Forms\Form;
use Filament\Tables\Table;
use App\Models\statusOrder;
use Illuminate\Support\Str;
use App\Models\StatusTicket;
use Filament\Resources\Resource;
use Filament\Tables\Filters\Filter;
use Illuminate\Support\Facades\Auth;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Section;
use Illuminate\Database\Eloquent\Model;
use Filament\Notifications\Notification;
use Filament\Forms\Components\DatePicker;
use Filament\Tables\Filters\SelectFilter;
use App\Filament\Resources\PreOrderResource\Pages;
use App\Filament\Resources\PreOrderResource\RelationManagers\TicketRelationManager;

class PreOrderResource extends Resource
{
    protected static ?string $model = PreOrder::class;
    protected static ?string $navigationIcon = 'heroicon-o-shopping-cart';
    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make()
                    ->columns(3)
                    ->schema([
                        //mmebuat nomer PO otomatis
                        Forms\Components\TextInput::make('code_po')
                            ->default(function () {
                                $date = date('ymd');
                                $randomNumber = mt_rand(100, 999); // 4 digit
                                $randomString = strtoupper(Str::random(2)); // 3 huruf kapital
                                return 'PO-' . $date . '-' . $randomNumber . '-' . $randomString;
                            })
                            ->readOnly()
                            ->disabled()
                            ->dehydrated(),
                        Select::make('product_id')
                            ->label('Product')
                            ->options(Product::all()->pluck('name_product', 'id'))
                            ->reactive()
                            ->required()
                            ->searchable(),
                        // Field Hidden untuk user_id (Staff)
                        Forms\Components\Hidden::make('user_id')
                            ->default(function () {
                                return Auth::id(); // Mengambil user_id yang sedang login
                            }),
                        Select::make('ticket_id')
                            ->label('User Complaint')
                            ->required()
                            ->options(function () {
                                // Ambil ID status yang perlu dikecualikan (misalnya 'approved')
                                $excludedStatusIds = StatusTicket::whereIn('name', ['rejected', 'requested'])->pluck('id');
                                // Ambil semua ticket_id yang sudah digunakan di pre_orders
                                $usedTicketIds = PreOrder::pluck('ticket_id')->toArray();
                                return Ticket::whereNotNull('status_ticket_id')
                                    ->whereNotIn('status_ticket_id', $excludedStatusIds)
                                    ->whereNotIn('id', $usedTicketIds) // Tiket yang sudah dipakai tidak ditampilkan
                                    ->pluck('code_ticket', 'id');
                            })
                            ->searchable()
                            ->visibleOn('create')
                            ->preload(),
                        Forms\Components\TextInput::make('total')
                            ->label('Quantity')
                            ->required()
                            ->rules(
                                'required',  // Kolom harus diisi
                            )
                            ->validationMessages([
                                'required' => 'Product stock is low.',
                            ])
                            ->placeholder('Enter quantity value')
                            ->numeric()
                            ->afterStateUpdated(function ($state, callable $get, callable $set) {
                                $productId = $get('product_id');
                                if ($productId) {
                                    $product = Product::find($productId);
                                    if ($product) {
                                        $finalStock = $product->final_stock;
                                        $name = $product->name_product;

                                        if ($state > $finalStock) {
                                            Notification::make()
                                                ->title('Stok Tidak Cukup')
                                                ->body("Stok {$name} saat ini hanya {$finalStock} unit.")
                                                ->danger()
                                                ->persistent()
                                                ->send();
                                            $set('total', null); // reset input jika stok tidak cukup
                                        }
                                    }
                                }
                            }),
                        Hidden::make('status_id')
                            ->default(fn() => statusOrder::where('name', 'requested')->value('id'))

                    ])
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('code_po')
                    ->numeric()
                    ->searchable(),
                Tables\Columns\TextColumn::make('product.name_product')
                    ->label('Product')
                    ->numeric()
                    ->searchable(),
                Tables\Columns\TextColumn::make('user.name')
                    ->label('User Complaint')
                    ->numeric()
                    ->searchable(),
                Tables\Columns\TextColumn::make('total')
                    ->label('Total')
                    ->numeric()
                    ->searchable(),
                Tables\Columns\TextColumn::make('status.name')
                    ->label('Status Tiket')
                    ->badge()
                    ->searchable()
                    ->colors([
                        'warning' => 'Request',
                        'success' => 'Approved',
                        'info' => 'Completed',
                        'danger' => 'Rejected',
                    ]),

                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                // Filter berdasarkan STATUS
                SelectFilter::make('status_id')
                    ->label('Status')
                    ->options(statusOrder::all()->pluck('name', 'id'))
                    ->searchable(),
                // Filter berdasarkan RENTANG TANGGAL CREATED_AT
                Filter::make('created_at')
                    ->form([
                        DatePicker::make('from')->label('From :'),
                        DatePicker::make('until')->label('Until :'),
                    ])
                    ->query(function ($query, array $data) {
                        return $query
                            ->when($data['from'], fn($q) => $q->whereDate('created_at', '>=', $data['from']))
                            ->when($data['until'], fn($q) => $q->whereDate('created_at', '<=', $data['until']));
                    }),
                SelectFilter::make('status_id')
                    ->label('Status')
                    ->options(
                        statusOrder::all()->pluck('name', 'id')->toArray()
                    )
                    ->multiple() // Ini kunci untuk multi-select
                    ->searchable()
                    ->placeholder('Select status..')
                    ->query(function ($query, array $data) {
                        if (! empty($data['values'])) {
                            $query->whereIn('status_id', $data['values']);
                        }
                    }),
            ])
            ->actions([
                Tables\Actions\Action::make('Approved')
                    ->hiddenLabel()
                    ->tooltip('Approved')
                    ->button()
                    ->size('xs')
                    ->icon('heroicon-o-check-circle')
                    ->color('info')
                    ->visible(function ($record) {
                        // Cek jika status_id adalah 'requested' (status awal)
                        return $record->status_id == statusOrder::where('name', 'requested')->value('id');
                    })
                    ->action(function ($record) {
                        // 1. Ambil ID status 'approved' dari status_order
                        $approvedStatusId = StatusOrder::where('name', 'approved')->value('id');

                        // 2. Update status_id pada record (orders)
                        $record->update([
                            'status_id' => $approvedStatusId,
                        ]);

                        // 3. Jika relasi ticket tersedia, update juga status_order_id di tabel ticket
                        if ($record->ticket) {
                            $record->ticket->update([
                                'status_order_id' => $approvedStatusId, // Asumsikan pakai status yang sama
                            ]);
                        }
                    })
                    ->requiresConfirmation()
                    ->modalIcon('heroicon-o-check-circle')
                    ->modalHeading('Pre Orders')
                    ->modalSubheading('Are you sure you want to approved this Orders ?')  // Deskripsi modal konfirmasi
                    ->modalButton('Yes'),

                Tables\Actions\Action::make('Rejected')
                    ->hiddenLabel()
                    ->tooltip('Rejected')
                    ->button()
                    ->size('xs')
                    ->icon('heroicon-o-x-circle')
                    ->color('info')
                    ->icon('heroicon-o-x-circle')
                    ->color('danger')
                    ->visible(function ($record) {
                        // Cek jika status_id adalah 'requested' (status awal)
                        return $record->status_id == statusOrder::where('name', 'requested')->value('id');
                    })
                    ->action(function ($record) {
                        // 1. Ambil ID status 'approved' dari status_order
                        $approvedStatusId = StatusOrder::where('name', 'rejected')->value('id');

                        // 2. Update status_id pada record (orders)
                        $record->update([
                            'status_id' => $approvedStatusId,
                        ]);

                        // 3. Jika relasi ticket tersedia, update juga status_order_id di tabel ticket
                        if ($record->ticket) {
                            $record->ticket->update([
                                'status_order_id' => $approvedStatusId, // Asumsikan pakai status yang sama
                            ]);
                        }
                    })
                    ->requiresConfirmation()
                    ->modalIcon('heroicon-o-x-circle')
                    ->modalHeading('Pre Orders')
                    ->modalSubheading('Are you sure you want to rejected this Orders ?')  // Deskripsi modal konfirmasi
                    ->modalButton('Yes'),

                Tables\Actions\Action::make('Completed')
                    ->hiddenLabel()
                    ->tooltip('Completed')
                    ->button()
                    ->size('xs')
                    ->color('info')
                    ->icon('heroicon-o-check')
                    ->color('info')
                    ->visible(function ($record) {
                        // Hanya tampilkan tombol "Completed" jika status_id adalah 'approved' atau 'rejected'
                        return in_array($record->status_id, [
                            statusOrder::where('name', 'approved')->value('id'),
                        ]);
                    })
                    ->action(function ($record) {
                        // 1. Ambil ID status 'approved' dari status_order
                        $approvedStatusId = StatusOrder::where('name', 'completed')->value('id');

                        // 2. Update status_id pada record (orders)
                        $record->update([
                            'status_id' => $approvedStatusId,
                        ]);

                        // 3. Jika relasi ticket tersedia, update juga status_order_id di tabel ticket
                        if ($record->ticket) {
                            $record->ticket->update([
                                'status_order_id' => $approvedStatusId, // Asumsikan pakai status yang sama
                            ]);
                        }
                    })
                    ->requiresConfirmation()
                    ->modalIcon('heroicon-o-check')
                    ->modalHeading('Pre Orders')
                    ->modalSubheading('Are you sure you want to completed this Orders ?')  // Deskripsi modal konfirmasi
                    ->modalButton('Yes'),


                Tables\Actions\ActionGroup::make([
                    Tables\Actions\EditAction::make(),
                    Tables\Actions\ViewAction::make(),
                ])
                    ->hiddenLabel()
                    ->icon('heroicon-m-ellipsis-vertical')
                    ->color('gray')
                    ->size('xs'),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            TicketRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListPreOrders::route('/'),
            'view' => Pages\ViewPreOrder::route('/{record}'),
        ];
    }

    // Middleware untuk Hak Akses Superadmin, Admin, User
    public static function canViewAny(): bool
    {
        return Auth::user()?->hasRole(['superadmin', 'admin']);
    }
    public static function canView(Model $record): bool
    {
        return Auth::user()?->hasRole(['superadmin', 'admin']);
    }

    public static function canCreate(): bool
    {
        return Auth::user()?->hasRole(['superadmin', 'admin']);
    }

    public static function canEdit(Model $record): bool
    {
        return Auth::user()?->hasRole(['superadmin', 'admin']);
    }

    public static function canDelete(Model $record): bool
    {
        return Auth::user()?->hasRole(['superadmin', 'admin']);
    }
}
