<?php

namespace App\Filament\Resources;

use App\Models\Task;
use App\Models\Submission;
use App\Models\TaskLog;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Section;
use Filament\Tables\Table;
use Filament\Tables\Columns\TextColumn;
use Filament\Actions\ViewAction;
use Filament\Support\Icons\Heroicon;
use Illuminate\Database\Eloquent\Builder;
use BackedEnum;

class MyTaskResource extends Resource
{
    protected static ?string $model = Task::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedClipboardDocumentCheck;

    protected static ?string $navigationLabel = 'My Tasks';

    protected static ?string $slug = 'my-tasks';

    protected static ?int $navigationSort = 1;

    // Only Production users (level 2) can see this
    public static function canViewAny(): bool
    {
        return auth()->user()?->role_level === 2;
    }

    // Scope to only show tasks assigned to the logged-in user
    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()->where('assignee_id', auth()->id());
    }

    public static function form(Schema $schema): Schema
    {
        return $schema->components([]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('title')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('status')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'pending'         => 'gray',
                        'in_progress'     => 'info',
                        'awaiting_review' => 'warning',
                        'revision'        => 'danger',
                        'ready_for_admin' => 'success',
                        'completed'       => 'success',
                        default           => 'gray',
                    })
                    ->sortable(),

                TextColumn::make('version')
                    ->label('Ver.')
                    ->sortable(),

                TextColumn::make('description')
                    ->limit(60)
                    ->toggleable(),

                TextColumn::make('created_at')
                    ->dateTime('d M Y')
                    ->sortable(),
            ])
            ->defaultSort('created_at', 'desc')
            ->recordActions([
                \Filament\Actions\Action::make('submit_work')
                    ->label('Submit Work')
                    ->icon(Heroicon::OutlinedCloudArrowUp)
                    ->color('success')
                    ->visible(fn (Task $record): bool => in_array($record->status, ['pending', 'in_progress', 'revision']))
                    ->form([
                        FileUpload::make('file_blend')
                            ->label('Blend File (.blend)')
                            ->disk('submissions')
                            ->directory('blend')
                            ->maxSize(512000)
                            ->required(),

                        FileUpload::make('file_mov')
                            ->label('Preview Video (.mp4 / .mov)')
                            ->disk('submissions')
                            ->directory('video')
                            ->acceptedFileTypes(['video/mp4', 'video/quicktime', 'video/x-msvideo'])
                            ->maxSize(512000)
                            ->required(),

                        Textarea::make('notes')
                            ->label('Notes (optional)')
                            ->rows(3),
                    ])
                    ->action(function (Task $record, array $data): void {
                        $oldStatus = $record->status;

                        $submission = Submission::create([
                            'task_id'       => $record->id,
                            'production_id' => auth()->id(),
                            'version'       => $record->version,
                            'file_blend_url' => $data['file_blend'],
                            'file_mov_url'   => $data['file_mov'],
                            'notes'          => $data['notes'] ?? null,
                        ]);

                        $record->update(['status' => 'awaiting_review']);

                        TaskLog::create([
                            'task_id'         => $record->id,
                            'user_id'         => auth()->id(),
                            'previous_status' => $oldStatus,
                            'new_status'      => 'awaiting_review',
                            'action_note'     => "v{$record->version} submitted for review.",
                        ]);

                        \Filament\Notifications\Notification::make()
                            ->title('Work submitted successfully!')
                            ->body('Your files have been sent to the reviewer.')
                            ->success()
                            ->send();
                    }),

                \Filament\Actions\Action::make('view_history')
                    ->label('History')
                    ->icon(Heroicon::OutlinedClock)
                    ->color('gray')
                    ->modalHeading(fn (Task $record) => "History — {$record->title}")
                    ->modalContent(fn (Task $record) => view('filament.task-history', ['task' => $record]))
                    ->modalSubmitAction(false),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => \App\Filament\Resources\MyTaskResource\Pages\ListMyTasks::route('/'),
        ];
    }
}
