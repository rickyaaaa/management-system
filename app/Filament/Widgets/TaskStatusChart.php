<?php

namespace App\Filament\Widgets;

use App\Models\Task;
use Filament\Widgets\ChartWidget;

class TaskStatusChart extends ChartWidget
{
    protected ?string $heading = 'Distribusi Status Tugas';

    protected static ?int $sort = 3;

    protected int|string|array $columnSpan = [
        'default' => 'full',
        'md' => 1,
        'xl' => 1,
    ];

    protected function getType(): string
    {
        return 'doughnut';
    }

    protected function getData(): array
    {
        $user = auth()->user();
        $level = $user->role_level;

        $baseQuery = Task::query();

        // Production sees only their own task distribution
        if ($level === 2) {
            $baseQuery->where('assignee_id', $user->id);
        }

        $statuses = [
            'pending'          => 'Pending',
            'in_progress'      => 'In Progress',
            'awaiting_review'  => 'Awaiting Review',
            'revision'         => 'Revision',
            'ready_for_admin'  => 'Ready for Admin',
            'completed'        => 'Completed',
        ];

        $counts = [];
        $labels = [];
        $colors = [];

        $colorMap = [
            'pending'         => '#94a3b8', // slate
            'in_progress'     => '#60a5fa', // blue
            'awaiting_review' => '#fbbf24', // amber
            'revision'        => '#f87171', // red
            'ready_for_admin' => '#34d399', // emerald
            'completed'       => '#22c55e', // green
        ];

        foreach ($statuses as $key => $label) {
            $count = (clone $baseQuery)->where('status', $key)->count();
            $counts[] = $count;
            $labels[] = $label;
            $colors[] = $colorMap[$key];
        }

        return [
            'datasets' => [
                [
                    'label'           => 'Jumlah Tugas',
                    'data'            => $counts,
                    'backgroundColor' => $colors,
                    'borderWidth'     => 0,
                    'hoverOffset'     => 8,
                ],
            ],
            'labels' => $labels,
        ];
    }

    protected function getOptions(): array
    {
        return [
            'plugins' => [
                'legend' => [
                    'position' => 'bottom',
                    'labels' => [
                        'padding' => 16,
                        'usePointStyle' => true,
                        'pointStyle' => 'circle',
                    ],
                ],
            ],
            'cutout' => '60%',
        ];
    }
}
