<?php

namespace App\Filament\Widgets;

use App\Models\TaskLog;
use Filament\Tables\Table;
use Filament\Tables\Columns\TextColumn;
use Filament\Widgets\TableWidget as BaseWidget;

class LatestTaskLogs extends BaseWidget
{
    protected static ?string $heading = 'Aktivitas Terbaru';

    protected static ?int $sort = 4;

    protected int|string|array $columnSpan = 'full';

    public function table(Table $table): Table
    {
        return $table
            ->query(
                TaskLog::with(['user', 'task'])
            )
            ->columns([
                TextColumn::make('user.username')
                    ->label('User')
                    ->icon('heroicon-m-user')
                    ->sortable(),

                TextColumn::make('task.title')
                    ->label('Tugas')
                    ->limit(35)
                    ->sortable(),

                TextColumn::make('previous_status')
                    ->label('Status Sebelum')
                    ->badge()
                    ->color(fn (?string $state): string => match ($state) {
                        'pending'         => 'gray',
                        'in_progress'     => 'info',
                        'awaiting_review' => 'warning',
                        'revision'        => 'danger',
                        'ready_for_admin' => 'success',
                        'completed'       => 'success',
                        default           => 'gray',
                    })
                    ->placeholder('—'),

                TextColumn::make('new_status')
                    ->label('Status Baru')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'pending'         => 'gray',
                        'in_progress'     => 'info',
                        'awaiting_review' => 'warning',
                        'revision'        => 'danger',
                        'ready_for_admin' => 'success',
                        'completed'       => 'success',
                        default           => 'gray',
                    }),

                TextColumn::make('action_note')
                    ->label('Keterangan')
                    ->limit(50)
                    ->placeholder('—'),

                TextColumn::make('created_at')
                    ->label('Waktu')
                    ->dateTime('d M Y H:i')
                    ->sortable(),
            ])
            ->defaultSort('created_at', 'desc')
            ->paginated([5, 10])
            ->defaultPaginationPageOption(5);
    }
}
