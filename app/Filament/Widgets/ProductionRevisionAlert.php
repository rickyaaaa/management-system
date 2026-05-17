<?php

namespace App\Filament\Widgets;

use App\Models\Task;
use App\Models\Review;
use Filament\Tables\Table;
use Filament\Tables\Columns\TextColumn;
use Filament\Widgets\TableWidget as BaseWidget;

class ProductionRevisionAlert extends BaseWidget
{
    protected static ?string $heading = '⚠️ Tugas Perlu Revisi';

    protected static ?int $sort = 2;

    protected int|string|array $columnSpan = 'full';

    public function table(Table $table): Table
    {
        $userId = auth()->id();

        return $table
            ->query(
                Task::query()->where('assignee_id', $userId)
                    ->where('status', 'revision')
                    ->with(['submissions' => function ($q) {
                        $q->latest()->with(['reviews' => function ($rq) {
                            $rq->where('status', 'rejected')->latest()->limit(1);
                        }]);
                    }])
            )
            ->defaultSort('updated_at', 'desc')
            ->columns([
                TextColumn::make('title')
                    ->label('Judul Tugas')
                    ->searchable()
                    ->sortable()
                    ->icon('heroicon-m-exclamation-triangle')
                    ->iconColor('danger'),

                TextColumn::make('version')
                    ->label('Versi')
                    ->formatStateUsing(fn (int $state): string => "v{$state}")
                    ->badge()
                    ->color('danger'),

                TextColumn::make('latest_feedback')
                    ->label('Feedback Reviewer')
                    ->getStateUsing(function (Task $record): string {
                        $latestSub = $record->submissions->first();
                        if (!$latestSub) {
                            return '—';
                        }
                        $latestReview = $latestSub->reviews->first();
                        return $latestReview?->feedback ?? '—';
                    })
                    ->wrap()
                    ->color('danger'),

                TextColumn::make('updated_at')
                    ->label('Dikembalikan')
                    ->since()
                    ->sortable(),
            ])
            ->paginated(false)
            ->emptyStateHeading('Tidak Ada Revisi')
            ->emptyStateDescription('Semua tugas Anda dalam kondisi baik. 🎉')
            ->emptyStateIcon('heroicon-o-check-circle');
    }
}
