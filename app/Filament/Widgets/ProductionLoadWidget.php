<?php

namespace App\Filament\Widgets;

use App\Models\User;
use App\Models\Task;
use Filament\Tables\Table;
use Filament\Tables\Columns\TextColumn;
use Filament\Widgets\TableWidget as BaseWidget;

class ProductionLoadWidget extends BaseWidget
{
    protected static ?string $heading = 'Beban Kerja Tim Production';

    protected static ?int $sort = 2;

    protected int|string|array $columnSpan = [
        'default' => 'full',
        'md' => 1,
        'xl' => 2,
    ];

    public function table(Table $table): Table
    {
        return $table
            ->query(
                User::query()->where('role_level', 2)
                    ->withCount([
                        'assignedTasks',
                        'assignedTasks as active_count' => function ($q) {
                            $q->whereIn('status', ['in_progress', 'pending', 'revision']);
                        },
                        'assignedTasks as awaiting_count' => function ($q) {
                            $q->where('status', 'awaiting_review');
                        },
                        'assignedTasks as completed_count' => function ($q) {
                            $q->where('status', 'completed');
                        },
                    ])
            )
            ->defaultSort('active_count', 'desc')
            ->columns([
                TextColumn::make('username')
                    ->label('Production User')
                    ->icon('heroicon-m-user')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('role_specialty')
                    ->label('Spesialisasi')
                    ->badge()
                    ->color('info')
                    ->placeholder('—'),

                TextColumn::make('assigned_tasks_count')
                    ->label('Total')
                    ->sortable()
                    ->alignCenter(),

                TextColumn::make('active_count')
                    ->label('Aktif')
                    ->sortable()
                    ->alignCenter()
                    ->color('warning'),

                TextColumn::make('awaiting_count')
                    ->label('Review')
                    ->sortable()
                    ->alignCenter()
                    ->color('info'),

                TextColumn::make('completed_count')
                    ->label('Selesai')
                    ->sortable()
                    ->alignCenter()
                    ->color('success'),
            ])
            ->paginated(false);
    }
}
