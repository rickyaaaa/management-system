<?php

namespace App\Filament\Widgets;

use App\Models\Submission;
use Filament\Tables\Table;
use Filament\Tables\Columns\TextColumn;
use Filament\Widgets\TableWidget as BaseWidget;

class ProductionSubmissionSummary extends BaseWidget
{
    protected static ?string $heading = 'Riwayat Submission Terakhir';

    protected static ?int $sort = 3;

    protected int|string|array $columnSpan = 'full';

    public function table(Table $table): Table
    {
        $userId = auth()->id();

        return $table
            ->query(
                Submission::query()->where('production_id', $userId)
                    ->with('task')
            )
            ->columns([
                TextColumn::make('task.title')
                    ->label('Tugas')
                    ->searchable()
                    ->sortable()
                    ->limit(40),

                TextColumn::make('version')
                    ->label('Versi')
                    ->formatStateUsing(fn (int $state): string => "v{$state}")
                    ->badge()
                    ->color('info'),

                TextColumn::make('file_url')
                    ->label('File Submission')
                    ->formatStateUsing(function (?string $state): string {
                        if (!$state) return '—';
                        $ext = strtolower(pathinfo($state, PATHINFO_EXTENSION));
                        $icon = in_array($ext, ['mp4', 'mov', 'avi']) ? '🎬' : '📦';
                        return "{$icon} " . basename($state);
                    })
                    ->limit(35),

                TextColumn::make('task.status')
                    ->label('Status Tugas')
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

                TextColumn::make('created_at')
                    ->label('Dikirim')
                    ->since()
                    ->sortable(),
            ])
            ->defaultSort('created_at', 'desc')
            ->paginated([5, 10])
            ->defaultPaginationPageOption(5)
            ->emptyStateHeading('Belum Ada Submission')
            ->emptyStateDescription('Upload file .blend dan .mov pertama Anda melalui menu My Tasks.')
            ->emptyStateIcon('heroicon-o-cloud-arrow-up');
    }
}
