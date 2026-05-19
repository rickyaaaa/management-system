<?php

namespace App\Filament\Resources;

use App\Models\User;
use App\Models\Task;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Select;
use Filament\Tables\Table;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\BadgeColumn;
use Filament\Actions\EditAction;
use Filament\Actions\CreateAction;
use Filament\Actions\DeleteAction;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Support\Icons\Heroicon;
use Illuminate\Database\Eloquent\Builder;
use BackedEnum;
use UnitEnum;

class TaskResource extends Resource
{
    protected static ?string $model = Task::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedClipboardDocumentList;

    protected static ?string $navigationLabel = 'Kelola Tugas';

    protected static string|UnitEnum|null $navigationGroup = 'Admin Panel';

    protected static ?int $navigationSort = 1;

    // Only Admin (level 1) can see this resource
    public static function canViewAny(): bool
    {
        return auth()->user()?->role_level === 1;
    }

    // Fix 1: Eager-load assignee to prevent N+1 queries
    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()->with(['assignee']);
    }

    public static function form(Schema $schema): Schema
    {
        return $schema->components([
            TextInput::make('title')
                ->required()
                ->maxLength(150)
                ->columnSpanFull(),

            Textarea::make('description')
                ->rows(4)
                ->columnSpanFull(),

            Select::make('assignee_id')
                ->label('Assign To')
                ->options(
                    User::where('role_level', 2)->pluck('username', 'id')
                )
                ->searchable()
                ->required(),

            Select::make('status')
                ->options([
                    'pending'          => 'Pending',
                    'in_progress'      => 'In Progress',
                    'awaiting_review'  => 'Awaiting Review',
                    'revision'         => 'Revision',
                    'ready_for_admin'  => 'Ready for Admin',
                    'completed'        => 'Completed',
                ])
                ->default('pending')
                ->required(),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('title')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('assignee.username')
                    ->label('Assigned To')
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

                TextColumn::make('created_at')
                    ->dateTime('d M Y')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->defaultSort('created_at', 'desc')
            ->filters([])
            ->recordActions([
                // ── View latest submission files ─────────────────────────────
                \Filament\Actions\Action::make('admin_view_files')
                    ->label('View Files')
                    ->icon(Heroicon::OutlinedArrowDownTray)
                    ->color('info')
                    ->visible(fn (Task $record): bool => in_array($record->status, ['ready_for_admin', 'completed']))
                    ->modalHeading(fn (Task $record): string => "Files — {$record->title} v{$record->version}")
                    ->modalContent(function (Task $record) {
                        $submission = $record->submissions()->latest()->first();
                        if (!$submission) {
                            return new \Illuminate\Support\HtmlString(
                                '<p class="text-sm text-gray-500 italic p-4 text-center">Belum ada submission untuk task ini.</p>'
                            );
                        }
                        return view('filament.submission-files', ['submission' => $submission]);
                    })
                    ->modalSubmitAction(false),

                // ── View review result (approved / feedback) ──────────────────
                \Filament\Actions\Action::make('admin_view_review')
                    ->label('Review Result')
                    ->icon(Heroicon::OutlinedClipboardDocumentCheck)
                    ->color('success')
                    ->visible(fn (Task $record): bool => in_array($record->status, ['ready_for_admin', 'completed']))
                    ->modalHeading(fn (Task $record): string => "Review Result — {$record->title}")
                    ->modalContent(function (Task $record) {
                        // Get latest submission then its latest review
                        $submission = $record->submissions()->latest()->first();
                        $review = $submission?->reviews()->latest()->first();

                        return new \Illuminate\Support\HtmlString(view('filament.admin-review-result', [
                            'task'       => $record,
                            'submission' => $submission,
                            'review'     => $review,
                        ])->render());
                    })
                    ->modalSubmitAction(false),

                EditAction::make(),
                DeleteAction::make(),
            ])
            ->toolbarActions([
                CreateAction::make(),
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index'  => \App\Filament\Resources\TaskResource\Pages\ListTasks::route('/'),
            'create' => \App\Filament\Resources\TaskResource\Pages\CreateTask::route('/create'),
            'edit'   => \App\Filament\Resources\TaskResource\Pages\EditTask::route('/{record}/edit'),
        ];
    }
}
