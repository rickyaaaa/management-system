<?php

namespace App\Filament\Pages;

use Filament\Pages\Dashboard as BaseDashboard;
use Filament\Support\Icons\Heroicon;
use App\Filament\Widgets\StatsOverview;
use App\Filament\Widgets\TaskStatusChart;
use App\Filament\Widgets\LatestTaskLogs;
use App\Filament\Widgets\ProductionLoadWidget;
use App\Filament\Widgets\ProductionRevisionAlert;
use App\Filament\Widgets\ProductionSubmissionSummary;
use App\Filament\Widgets\ReviewerQueueWidget;
use BackedEnum;

class Dashboard extends BaseDashboard
{
    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedHome;

    public function getColumns(): int|array
    {
        return [
            'default' => 1,
            'md' => 2,
            'xl' => 3,
        ];
    }

    public function getWidgets(): array
    {
        $user = auth()->user();
        $level = $user?->role_level;

        // — Super Admin (Lvl 1): Helicopter view —
        if ($level === 1) {
            return [
                StatsOverview::class,
                ProductionLoadWidget::class,
                TaskStatusChart::class,
                LatestTaskLogs::class,
            ];
        }

        // — Production (Lvl 2): Active work + revision alerts —
        if ($level === 2) {
            return [
                StatsOverview::class,
                ProductionRevisionAlert::class,
                ProductionSubmissionSummary::class,
            ];
        }

        // — Reviewer (Lvl 3): QC queue focus —
        if ($level === 3) {
            return [
                StatsOverview::class,
                ReviewerQueueWidget::class,
                TaskStatusChart::class,
            ];
        }

        return [
            StatsOverview::class,
        ];
    }
}
