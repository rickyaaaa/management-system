<?php

namespace App\Filament\Resources;

use App\Models\User;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Forms\Components\TextInput;
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
use BackedEnum;
use UnitEnum;

class UserResource extends Resource
{
    protected static ?string $model = User::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedUsers;

    protected static ?string $navigationLabel = 'Kelola User';

    protected static string|UnitEnum|null $navigationGroup = 'Admin Panel';

    protected static ?int $navigationSort = 2;

    // Only Admin (level 1) can see this resource
    public static function canViewAny(): bool
    {
        return auth()->user()?->role_level === 1;
    }

    public static function form(Schema $schema): Schema
    {
        return $schema->components([
            TextInput::make('name')
                ->label('Full Name')
                ->required(),

            TextInput::make('username')
                ->required()
                ->unique(ignoreRecord: true),

            TextInput::make('email')
                ->email()
                ->unique(ignoreRecord: true),

            TextInput::make('password')
                ->password()
                ->required(fn ($record) => $record === null)
                ->dehydrated(fn ($state) => filled($state))
                ->dehydrateStateUsing(fn ($state) => \Illuminate\Support\Facades\Hash::make($state))
                ->label(fn ($record) => $record ? 'New Password (leave blank to keep current)' : 'Password'),

            Select::make('role_level')
                ->label('Role')
                ->options([
                    1 => 'Admin',
                    2 => 'Production',
                    3 => 'Reviewer',
                ])
                ->required()
                ->default(2)
                ->reactive(),

            Select::make('role_specialty')
                ->label('Specialty')
                ->options([
                    'Modeling'   => 'Modeling',
                    'Texturing'  => 'Texturing',
                    'RIG'        => 'RIG',
                    'Animation'  => 'Animation',
                    'LRC'        => 'LRC',
                ])
                ->visible(fn ($get) => (int)$get('role_level') === 2)
                ->nullable(),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('username')
                    ->searchable(),

                TextColumn::make('role_level')
                    ->label('Role')
                    ->badge()
                    ->formatStateUsing(fn (int $state): string => match ($state) {
                        1 => 'Admin',
                        2 => 'Production',
                        3 => 'Reviewer',
                        default => 'Unknown',
                    })
                    ->color(fn (int $state): string => match ($state) {
                        1 => 'danger',
                        2 => 'info',
                        3 => 'success',
                        default => 'gray',
                    })
                    ->sortable(),

                TextColumn::make('role_specialty')
                    ->label('Specialty')
                    ->placeholder('—'),

                TextColumn::make('created_at')
                    ->dateTime('d M Y')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->defaultSort('role_level')
            ->recordActions([
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
            'index'  => \App\Filament\Resources\UserResource\Pages\ListUsers::route('/'),
            'create' => \App\Filament\Resources\UserResource\Pages\CreateUser::route('/create'),
            'edit'   => \App\Filament\Resources\UserResource\Pages\EditUser::route('/{record}/edit'),
        ];
    }
}
