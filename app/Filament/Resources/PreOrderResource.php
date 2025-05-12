<?php

namespace App\Filament\Resources;

use Filament\Forms;
use Filament\Tables;
use App\Models\Status;
use App\Models\Product;
use App\Models\PreOrder;
use Filament\Forms\Form;
use Filament\Tables\Table;
use Illuminate\Support\Str;
use Filament\Resources\Resource;
use Illuminate\Support\Facades\Auth;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Section;
use Illuminate\Database\Eloquent\Model;
use Filament\Notifications\Notification;
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
                    ->columns(4)
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
                            ->relationship('ticket', 'code_ticket')
                            ->searchable()
                            ->visibleOn('create')
                            ->preload(),
                        Forms\Components\TextInput::make('total')
                            ->label('Quantity')
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

                        Select::make('status_id')
                            ->label('Status Tiket')
                            ->options(Status::all()->pluck('name', 'id'))
                            ->visibleOn('edit')
                            ->live()
                            ->afterStateUpdated(function ($state, callable $set, $record) {
                                if ($record->ticket) {
                                    $record->ticket->update([
                                        'status_id' => $state,
                                    ]);
                                }
                            })
                            ->default(function ($record) {
                                return $record->ticket?->status_id ?? Status::where('name', 'requested')->value('id');
                            })
                            ->disabledOn('create')

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
                Tables\Columns\TextColumn::make('ticket.status.name')
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
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\ViewAction::make(),
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
            // 'create' => Pages\CreatePreOrder::route('/create'),
            // 'edit' => Pages\EditPreOrder::route('/{record}/edit'),
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
