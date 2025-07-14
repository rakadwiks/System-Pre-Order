<?php

namespace App\Filament\Resources;

use App\Mail\TicketApprovedMail;
use Filament\Forms;
use App\Models\User;
use Filament\Tables;
use App\Models\Ticket;
use Filament\Forms\Form;
use Filament\Tables\Table;
use App\Models\statusOrder;
use Illuminate\Support\Str;
use App\Models\StatusTicket;
use App\Mail\TicketRejectedMail;
use Filament\Resources\Resource;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\Section;
use Filament\Tables\Columns\TextColumn;
use Illuminate\Database\Eloquent\Model;
use Filament\Notifications\Notification;
use Filament\Tables\Columns\ImageColumn;
use Filament\Forms\Components\FileUpload;
use Filament\Tables\Actions\ExportAction;
use App\Filament\Exports\PreOrderExporter;
use App\Filament\Resources\TicketResource\Pages;
use Filament\Actions\Exports\Enums\ExportFormat;
use AlperenErsoy\FilamentExport\Actions\FilamentExportHeaderAction;

class TicketResource extends Resource
{
    protected static ?string $model = Ticket::class;

    protected static ?string $navigationIcon = 'heroicon-o-ticket';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make([
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
                    Forms\Components\Textarea::make('description')
                        ->label('Description')
                        ->required()
                        ->rows(3),
                    Hidden::make('status_ticket_id')
                        ->default(fn() => StatusTicket::where('name', 'requested')
                            ->value('id')),
                    Hidden::make('status_order_id')
                        ->default(fn() => statusOrder::where('name', 'requested')
                            ->value('id')),
                    Hidden::make('role_id')
                        ->default(fn() => Auth::user()?->role_id)
                ])->compact(),

                Section::make('Upload Error')
                    ->schema([
                        FileUpload::make('photos')
                            ->label('Photos')
                            ->image()
                            ->disk('public')
                            ->directory('ticket-photos')
                            ->multiple()
                            ->required()
                            ->preserveFilenames()
                            ->reorderable()
                            ->downloadable()
                            ->previewable()
                            // Saat form edit: konversi dari associative array ke array biasa
                            ->formatStateUsing(function ($state) {
                                if (is_string($state)) {
                                    $state = json_decode($state, true);
                                }
                                return is_array($state) ? array_values($state) : [];
                            })
                            ->dehydrateStateUsing(function (?array $state) {
                                if (!$state)
                                    return null;
                                return collect($state)
                                    ->mapWithKeys(fn($file) => [Str::uuid()->toString() => $file])
                                    ->toArray();
                            })
                            ->helperText('Upload photo PNG/JPG max 2MB.')
                    ])
                    ->compact()
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('code_ticket')
                    ->label('Code Ticket')
                    ->searchable(),
                ImageColumn::make('photos')
                    ->label('Photo')
                    ->getStateUsing(function ($record) {
                        // Ubah JSON string menjadi array
                        $photos = is_string($record->photos)
                            ? json_decode($record->photos, true)
                            : $record->photos;

                        // Ambil value pertama dari associative array
                        if (is_array($photos) && count($photos) > 0) {
                            $firstPath = array_values($photos)[0];
                            return asset('storage/' . $firstPath); // hasil: http://localhost/storage/ticket-photos/xxx.png
                            // return $firstPath; // hasil: http://localhost/storage/ticket-photos/xxx.png
                        }

                        return null;
                    })
                    ->disk('public'),
                Tables\Columns\TextColumn::make('user.name')
                    ->label('User Complaint')
                    ->searchable(),
                Tables\Columns\TextColumn::make('description')
                    ->label('Description')
                    ->searchable(),
                Tables\Columns\TextColumn::make('statusTicket.name')
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
                Tables\Actions\Action::make('Approved')
                    ->hiddenLabel()
                    ->tooltip('Approved')
                    ->button()
                    ->size('xs')
                    ->icon('heroicon-o-check-circle')
                    ->color('info')
                    ->hidden(fn() => Auth::user()?->hasRole('User')) // menyembunyikan role user
                    ->visible(function ($record) {
                        // Cek jika status_id adalah 'requested' (status awal)
                        return $record->status_ticket_id == StatusTicket::where('name', 'requested')->value('id');
                    })
                    ->requiresConfirmation()
                    ->modalIcon('heroicon-o-check-circle')
                    ->modalHeading('Tickets')
                    ->modalSubheading('Are you sure you want to approved this Tickets ?')  // Deskripsi modal konfirmasi
                    ->modalButton('Yes')
                    ->form(fn(Ticket $record) => [
                        Forms\Components\TextInput::make('code_ticket')
                            ->label('Ticket Code')
                            ->default($record->code_ticket)
                            ->hidden()
                            ->disabled(),
                        Forms\Components\TextInput::make('email')
                            ->label('User Email')
                            ->email()
                            ->hidden()
                            ->default($record->user?->email) // Ambil dari relasi user
                            ->disabled(), // opsional: agar tidak bisa diedit
                        // ->required() tidak perlu kalau disabled
                    ])
                    ->action(function (array $data, Ticket $record) {
                        // ambil email langsung dari relasi user
                        $email = $record->user?->email;
                        if (!$email) {
                            throw new \Exception('User email not found.');
                        }
                        Mail::to($email)->send(
                            new TicketApprovedMail($record)
                        );
                        // Update status ke 'approved'
                        $approvedStatusId = StatusTicket::where('name', 'approved')->value('id');
                        $record->update(['status_ticket_id' => $approvedStatusId]);

                        // Update status ticket dan approved_by
                        $record->update([
                            'status_ticket_id' => $approvedStatusId,
                            'approved_by' => Auth::id(),
                        ]);
                    }),

                Tables\Actions\Action::make('Rejected')
                    ->hiddenLabel()
                    ->tooltip('Rejected')
                    ->button()
                    ->size('xs')
                    ->color('info')
                    ->icon('heroicon-o-x-circle')
                    ->color('danger')
                    ->hidden(fn() => Auth::user()?->hasRole('User')) // menyembunyikan role user
                    ->visible(function ($record) {
                        // Cek jika status_ticket_id adalah 'requested' (status awal)
                        return $record->status_ticket_id == StatusTicket::where('name', 'requested')->value('id');
                    })
                    ->action(function ($record) {
                        // Update status ke 'rejected'
                        $rejectedStatusId = StatusTicket::where('name', 'rejected')->value('id');
                        $record->update(['status_ticket_id' => $rejectedStatusId]);
                    })
                    ->requiresConfirmation()
                    ->modalIcon('heroicon-o-x-circle')
                    ->modalHeading('Tickets')
                    ->modalSubheading('Are you sure you want to rejected this Tickets ?')  // Deskripsi modal konfirmasi
                    ->modalButton('Yes')
                    ->form(fn(Ticket $record) => [
                        Forms\Components\TextInput::make('code_ticket')
                            ->label('Ticket Code')
                            ->default($record->code_ticket)
                            ->hidden()
                            ->disabled(),
                        Forms\Components\TextInput::make('email')
                            ->label('User Email')
                            ->email()
                            ->hidden()
                            ->default($record->user?->email) // Ambil dari relasi user
                            ->disabled(), // opsional: agar tidak bisa diedit
                        // ->required() tidak perlu kalau disabled
                        Forms\Components\Textarea::make('reason')
                            ->label('Rejection Reason')
                            ->required()
                            ->rows(3)
                            ->placeholder('Enter reason for rejection...')
                    ])
                    ->action(function (array $data, Ticket $record) {
                        // ambil email langsung dari relasi user
                        $email = $record->user?->email;
                        if (!$email) {
                            throw new \Exception('User email not found.');
                        }
                        Mail::to($email)->send(
                            new TicketRejectedMail($data['reason'], $record)
                        );
                        // Update status ke 'rejected'
                        $rejectedStatusId = StatusTicket::where('name', 'rejected')->value('id');
                        $record->update(['status_ticket_id' => $rejectedStatusId]);

                        // Update status ticket dan approved_by
                        $record->update([
                            'status_ticket_id' => $rejectedStatusId,
                            'rejected_by' => Auth::id(),
                        ]);
                    }),
                Tables\Actions\EditAction::make()->hidden(fn() => Auth::user()?->hasRole('Admin')), // menyembunyikan role user,
                Tables\Actions\ViewAction::make(),


            ])
            ->headerActions([
                FilamentExportHeaderAction::make('Export')
                    ->visible(fn() => Auth::user()?->hasRole(['SuperAdmin', 'Admin']))
                    ->modalHeading('Export')
                    ->modalDescription('Select the file format and data you want to export.')
                    ->color('gray')
                    ->size('xs')
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
            'view' => Pages\ViewTicket::route('/{record}'),
        ];
    }

    //menambahkan fitur notifikasi badge pada sidebar
    public static function getNavigationBadge(): ?string
    {
        $user = Auth::user();
        // Cek apakah user memiliki salah satu role yang diizinkan
        if (!$user?->hasRole(['SuperAdmin', 'Admin', 'SuperUser'])) {
            return null;
        }
        $count = Ticket::whereHas('statusTicket', function ($query) {
            $query->where('name', 'requested');
        })->count();
        return $count > 0 ? (string) $count : null;
    }
}
