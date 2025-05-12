<?php

namespace App\Filament\Resources;

use Filament\Forms;
use Filament\Tables;
use App\Models\Status;
use App\Models\Ticket;
use Filament\Forms\Form;
use Filament\Tables\Table;
use Illuminate\Support\Str;
use Filament\Resources\Resource;
use Illuminate\Support\Facades\Auth;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\Section;
use App\Filament\Resources\TicketResource\Pages;

class TicketResource extends Resource
{
    protected static ?string $model = Ticket::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make()
                    ->schema([
                        Forms\Components\TextInput::make('code_ticket')
                            ->default(function () {
                                $date = date('ymd');
                                $randomNumber = mt_rand(100, 999); // 4 digit
                                $randomString = strtoupper(Str::random(3)); // 3 huruf kapital
                                return 'TX-' . $date . '-' . $randomNumber . '-' . $randomString;
                            })
                            ->readOnly()
                            ->disabled()
                            ->dehydrated(),
                        // Field Hidden untuk user_id (Staff)
                        Forms\Components\Hidden::make('user_id')
                            ->default(function () {
                                return Auth::id(); // Mengambil user_id yang sedang login
                            }),
                        Forms\Components\MarkdownEditor::make('description')
                            ->toolbarButtons([
                                'attachFiles',
                                'blockquote',
                                'bold',
                                'bulletList',
                                'codeBlock',
                                'heading',
                                'italic',
                                'link',
                                'orderedList',
                                'redo',
                                'strike',
                                'table',
                                'undo',
                            ]),
                        Hidden::make('status_id')
                            ->default(fn() => Status::where('name', 'requested')
                                ->value('id')),

                    ])
                //mmebuat nomer PO otomatis
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('code_ticket')
                    ->label('Code Ticket')
                    ->searchable(),
                Tables\Columns\TextColumn::make('user.name')
                    ->label('User Complaint')
                    ->searchable(),
                Tables\Columns\TextColumn::make('description')
                    ->label('Description')
                    ->searchable(),
                Tables\Columns\TextColumn::make('status.name')
                    ->label('Status')
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
                // Tables\Actions\EditAction::make(),
                Tables\Actions\ActionGroup::make([
                    Tables\Actions\Action::make('Approved')
                        ->label('Approved')
                        ->icon('heroicon-o-check-circle')
                        ->color('success')
                        ->action(function ($record) {
                            // Ambil ID untuk status 'approved'
                            $approvedStatusId = Status::where('name', 'approved')->value('id');
                            $record->update(['status_id' => $approvedStatusId]);
                        })
                        ->requiresConfirmation()
                        ->modalHeading('Approve Ticket')
                        ->modalSubheading('Are you sure you want to approve this ticket?')  // Deskripsi modal konfirmasi
                        ->modalButton('Yes, approve'),
                    Tables\Actions\Action::make('Rejected')
                        ->label('Rejected')
                        ->icon('heroicon-o-x-circle')
                        ->color('danger')
                        ->action(function ($record) {
                            // Ambil ID untuk status 'approved'
                            $rejectedStatusId = Status::where('name', 'rejected')->value('id');
                            $record->update(['status_id' => $rejectedStatusId]);
                        })
                        ->requiresConfirmation()
                        ->modalHeading('Reject Ticket')
                        ->modalSubheading('Are you sure you want to reject this ticket?')  // Deskripsi modal konfirmasi
                        ->modalButton('Yes, reject'),
                ]),

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
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListTickets::route('/'),
        ];
    }

    // membuat default request untuk user
    protected static function booted()
    {
        static::creating(function ($ticket) {
            $ticket->status_id = Status::where('name', 'requested')->value('id');
        });
    }

    public static function getNavigationBadge(): ?string
    {
        $user = Auth::user();
        // Cek apakah user memiliki salah satu role yang diizinkan
        if (! $user?->hasRole(['superadmin', 'admin'])) {
            return null;
        }
        $count = Ticket::whereHas('status', function ($query) {
            $query->where('name', 'requested');
        })->count();
        return $count > 0 ? (string) $count : null;
    }
}
