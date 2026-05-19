<?php

namespace App\Filament\Resources;

use App\Models\Submission;
use App\Models\Review;
use App\Models\TaskLog;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Forms\Components\Textarea;
use Filament\Tables\Table;
use Filament\Tables\Columns\TextColumn;
use Filament\Support\Icons\Heroicon;
use Illuminate\Database\Eloquent\Builder;
use BackedEnum;

class ReviewResource extends Resource
{
    protected static ?string $model = Submission::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedMagnifyingGlass;

    protected static ?string $navigationLabel = 'Review Queue';

    protected static ?string $slug = 'review-queue';

    protected static ?int $navigationSort = 1;

    // Only Reviewer users (level 3) can see this
    public static function canViewAny(): bool
    {
        return auth()->user()?->role_level === 3;
    }

    // Only show submissions awaiting review
    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()
            ->whereHas('task', fn ($q) => $q->where('status', 'awaiting_review'));
    }

    public static function form(Schema $schema): Schema
    {
        return $schema->components([]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('task.title')
                    ->label('Task')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('production.username')
                    ->label('Submitted By')
                    ->sortable(),

                TextColumn::make('version')
                    ->label('Ver.')
                    ->sortable(),

                TextColumn::make('notes')
                    ->label('Production Notes')
                    ->limit(60)
                    ->placeholder('—'),

                TextColumn::make('task.status')
                    ->label('Status')
                    ->badge()
                    ->color('warning'),

                TextColumn::make('created_at')
                    ->label('Submitted At')
                    ->dateTime('d M Y H:i')
                    ->sortable(),
            ])
            ->defaultSort('created_at', 'desc')
            ->recordActions([
                \Filament\Actions\Action::make('view_files')
                    ->label('View Files')
                    ->icon(Heroicon::OutlinedEye)
                    ->color('info')
                    ->modalHeading(fn (Submission $record) => "Files — {$record->task->title} v{$record->version}")
                    ->modalContent(fn (Submission $record) => view('filament.submission-files', ['submission' => $record]))
                    ->modalSubmitAction(false),

                \Filament\Actions\Action::make('approve')
                    ->label('Approve')
                    ->icon(Heroicon::OutlinedCheckCircle)
                    ->color('success')
                    ->visible(fn (Submission $record): bool => $record->task?->status === 'awaiting_review')
                    ->requiresConfirmation()
                    ->modalHeading('Approve Submission')
                    ->modalDescription('This will forward the task to the admin as ready.')
                    ->action(function (Submission $record): void {
                        // Fix 2: Null-safe guard
                        if (! $record->task) return;

                        $oldStatus = $record->task->status;

                        // Fix 3: Wrap all writes in a transaction for atomicity
                        \DB::transaction(function () use ($record, $oldStatus) {
                            Review::create([
                                'submission_id' => $record->id,
                                'reviewer_id'   => auth()->id(),
                                'status'        => 'approved',
                                'feedback'      => null,
                            ]);

                            $record->task->update(['status' => 'ready_for_admin']);

                            TaskLog::create([
                                'task_id'         => $record->task_id,
                                'user_id'         => auth()->id(),
                                'previous_status' => $oldStatus,
                                'new_status'      => 'ready_for_admin',
                                'action_note'     => 'Approved by reviewer. Forwarded to admin.',
                            ]);
                        });

                        \Filament\Notifications\Notification::make()
                            ->title('Submission approved!')
                            ->body('Task has been forwarded to admin.')
                            ->success()
                            ->send();
                    }),

                \Filament\Actions\Action::make('reject')
                    ->label('Reject')
                    ->icon(Heroicon::OutlinedXCircle)
                    ->color('danger')
                    ->visible(fn (Submission $record): bool => $record->task?->status === 'awaiting_review')
                    ->form([
                        Textarea::make('feedback')
                            ->label('Rejection Reason / Feedback')
                            ->required()
                            ->rows(4),
                    ])
                    ->action(function (Submission $record, array $data): void {
                        // Fix 2: Null-safe guard
                        if (! $record->task) return;

                        $oldStatus = $record->task->status;

                        // Fix 3: Wrap all writes in a transaction for atomicity
                        \DB::transaction(function () use ($record, $data, $oldStatus) {
                            Review::create([
                                'submission_id' => $record->id,
                                'reviewer_id'   => auth()->id(),
                                'status'        => 'rejected',
                                'feedback'      => $data['feedback'],
                            ]);

                            // Fix 2: Single update() — no N+1, no memory desync
                            $record->task->update([
                                'version' => $record->task->version + 1,
                                'status'  => 'revision',
                            ]);

                            TaskLog::create([
                                'task_id'         => $record->task_id,
                                'user_id'         => auth()->id(),
                                'previous_status' => $oldStatus,
                                'new_status'      => 'revision',
                                'action_note'     => "Rejected: {$data['feedback']}",
                            ]);
                        });

                        \Filament\Notifications\Notification::make()
                            ->title('Submission rejected')
                            ->body('Task returned to production for revision.')
                            ->danger()
                            ->send();
                    }),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => \App\Filament\Resources\ReviewResource\Pages\ListReviews::route('/'),
        ];
    }
}
