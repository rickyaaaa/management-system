<?php

namespace App\Filament\Widgets;

use App\Models\Task;
use App\Models\Review;
use Filament\Tables\Table;
use Filament\Tables\Columns\TextColumn;
use Filament\Widgets\TableWidget as BaseWidget;

class ReviewerQueueWidget extends BaseWidget
{
    protected static ?string $heading = '📋 Antrean Review Hari Ini';

    protected static ?int $sort = 2;

    protected int|string|array $columnSpan = 'full';

    public function table(Table $table): Table
    {
        return $table
            ->query(
                Task::query()->where('status', 'awaiting_review')
                    ->with(['assignee', 'submissions' => function ($q) {
                        $q->latest()->limit(1);
                    }])
            )
            ->defaultSort('updated_at', 'asc') // Oldest first = most urgent
            ->columns([
                TextColumn::make('title')
                    ->label('Judul Tugas')
                    ->searchable()
                    ->sortable()
                    ->icon('heroicon-m-document-text'),

                TextColumn::make('assignee.username')
                    ->label('Dari Production')
                    ->sortable()
                    ->icon('heroicon-m-user'),

                TextColumn::make('version')
                    ->label('Versi')
                    ->formatStateUsing(fn (int $state): string => "v{$state}")
                    ->badge()
                    ->color('warning'),

                TextColumn::make('latest_notes')
                    ->label('Catatan Production')
                    ->getStateUsing(function (Task $record): string {
                        $latestSub = $record->submissions->first();
                        return $latestSub?->notes ?? '—';
                    })
                    ->limit(50)
                    ->placeholder('—'),

                TextColumn::make('updated_at')
                    ->label('Menunggu Sejak')
                    ->since()
                    ->sortable()
                    ->color(function (Task $record): string {
                        // Highlight if waiting for more than 24 hours
                        $hours = $record->updated_at->diffInHours(now());
                        if ($hours >= 24) {
                            return 'danger';
                        }
                        if ($hours >= 8) {
                            return 'warning';
                        }
                        return 'gray';
                    }),
            ])
            ->paginated(false)
            ->emptyStateHeading('Antrean Kosong!')
            ->emptyStateDescription('Tidak ada tugas yang menunggu review saat ini. 🎉')
            ->emptyStateIcon('heroicon-o-check-badge');
    }

    /**
     * Additional info for the widget header
     */
    public function getTableDescription(): ?string
    {
        $count = Task::where('status', 'awaiting_review')->count();
        if ($count === 0) {
            return null;
        }

        return "⏳ {$count} tugas menunggu review Anda";
    }
}
