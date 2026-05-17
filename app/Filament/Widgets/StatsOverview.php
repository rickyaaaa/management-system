<?php

namespace App\Filament\Widgets;

use App\Models\Task;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class StatsOverview extends BaseWidget
{
    protected static ?int $sort = 1;

    protected function getStats(): array
    {
        $user = auth()->user();
        $level = $user->role_level;

        // Build the base query depending on role
        $baseQuery = Task::query();

        if ($level === 2) {
            // Production: only their own assigned tasks
            $baseQuery->where('assignee_id', $user->id);
        }
        // Lvl 1 (Admin) & Lvl 3 (Reviewer): see all tasks

        $total       = (clone $baseQuery)->count();
        $pending     = (clone $baseQuery)->where('status', 'pending')->count();
        $inProgress  = (clone $baseQuery)->where('status', 'in_progress')->count();
        $awaiting    = (clone $baseQuery)->where('status', 'awaiting_review')->count();
        $revision    = (clone $baseQuery)->where('status', 'revision')->count();
        $readyAdmin  = (clone $baseQuery)->where('status', 'ready_for_admin')->count();
        $completed   = (clone $baseQuery)->where('status', 'completed')->count();

        // ── Super Admin ──
        if ($level === 1) {
            return [
                Stat::make('Total Tugas', $total)
                    ->description('Seluruh proyek')
                    ->descriptionIcon('heroicon-m-clipboard-document-list')
                    ->color('primary'),

                Stat::make('Selesai', $completed)
                    ->description(($total > 0 ? round(($completed / $total) * 100) : 0) . '% dari total')
                    ->descriptionIcon('heroicon-m-check-circle')
                    ->color('success'),

                Stat::make('Dalam Proses', $inProgress)
                    ->description('Sedang dikerjakan')
                    ->descriptionIcon('heroicon-m-arrow-path')
                    ->color('info'),

                Stat::make('Perlu Persetujuan', $readyAdmin)
                    ->description('Menunggu approval Anda')
                    ->descriptionIcon('heroicon-m-hand-raised')
                    ->color('warning'),

                Stat::make('Revisi', $revision)
                    ->description('Dikembalikan ke Production')
                    ->descriptionIcon('heroicon-m-arrow-uturn-left')
                    ->color('danger'),

                Stat::make('Menunggu Review', $awaiting)
                    ->description('Di antrean Reviewer')
                    ->descriptionIcon('heroicon-m-clock')
                    ->color('gray'),
            ];
        }

        // ── Production ──
        if ($level === 2) {
            return [
                Stat::make('Tugas Saya', $total)
                    ->description('Total tugas yang di-assign')
                    ->descriptionIcon('heroicon-m-clipboard-document-list')
                    ->color('primary'),

                Stat::make('Aktif', $inProgress)
                    ->description('Sedang dikerjakan')
                    ->descriptionIcon('heroicon-m-play')
                    ->color('info'),

                Stat::make('Perlu Revisi', $revision)
                    ->description('Ada feedback dari reviewer')
                    ->descriptionIcon('heroicon-m-exclamation-triangle')
                    ->color('danger'),

                Stat::make('Selesai', $completed)
                    ->description(($total > 0 ? round(($completed / $total) * 100) : 0) . '% selesai')
                    ->descriptionIcon('heroicon-m-check-circle')
                    ->color('success'),
            ];
        }

        // ── Reviewer ──
        if ($level === 3) {
            return [
                Stat::make('Antrean Review', $awaiting)
                    ->description('Tugas menunggu QC Anda')
                    ->descriptionIcon('heroicon-m-queue-list')
                    ->color('warning'),

                Stat::make('Total Tugas', $total)
                    ->description('Seluruh proyek')
                    ->descriptionIcon('heroicon-m-clipboard-document-list')
                    ->color('primary'),

                Stat::make('Sudah Di-review', $completed + $readyAdmin)
                    ->description('Approved & completed')
                    ->descriptionIcon('heroicon-m-check-badge')
                    ->color('success'),

                Stat::make('Dalam Revisi', $revision)
                    ->description('Rejected, menunggu perbaikan')
                    ->descriptionIcon('heroicon-m-arrow-uturn-left')
                    ->color('danger'),
            ];
        }

        return [];
    }
}
