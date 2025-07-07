<?php

namespace App\Filament\Widgets;


use App\Models\Team;
use Illuminate\Database\Eloquent\Builder;
use Filament\Widgets\TableWidget as BaseWidget;
use Filament\Tables\Columns\TextColumn;

class TeamInfoWidget extends BaseWidget
{
    protected static ?string $heading = 'Team Info';
    protected int | string | array $columnSpan = 1;
    protected static ?int $sort = 5;

    protected function getTitle(): string
    {
        return 'Team Info';
    }

    protected function getTableQuery(): Builder
    {
        return Team::query();
    }

    public function getTableColumns(): array
    {
        return [
            TextColumn::make('name_team')->label('Team Name')
                ->sortable()
                ->searchable(),
        ];
    }
}
