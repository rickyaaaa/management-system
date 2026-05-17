<?php

namespace App\Filament\Resources;

use App\Models\Role;
use App\Models\Permission;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\CheckboxList;
use Filament\Schemas\Components\Section;
use Filament\Tables\Table;
use Filament\Tables\Columns\TextColumn;
use Filament\Actions\EditAction;
use Filament\Actions\CreateAction;
use Filament\Actions\DeleteAction;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Support\Icons\Heroicon;
use BackedEnum;
use UnitEnum;

class RoleResource extends Resource
{
    protected static ?string $model = Role::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedShieldCheck;

    protected static ?string $navigationLabel = 'Kelola Hak Akses';

    protected static string|UnitEnum|null $navigationGroup = 'Admin Panel';

    protected static ?int $navigationSort = 3;

    protected static ?string $modelLabel = 'Role';

    protected static ?string $pluralModelLabel = 'Roles';

    // Only Admin (level 1) can access this resource
    public static function canViewAny(): bool
    {
        return auth()->user()?->role_level === 1;
    }

    public static function form(Schema $schema): Schema
    {
        // Group permissions by their group field
        $groupedPermissions = Permission::all()->groupBy('group');

        $sections = [];

        // Basic info section
        $sections[] = Section::make('Informasi Role')
            ->schema([
                TextInput::make('name')
                    ->label('Nama Role (slug)')
                    ->required()
                    ->unique(ignoreRecord: true)
                    ->alphaDash()
                    ->maxLength(50)
                    ->placeholder('contoh: editor_3d')
                    ->helperText('Gunakan huruf kecil, angka, underscore, atau strip.'),

                TextInput::make('display_name')
                    ->label('Nama Tampilan')
                    ->maxLength(100)
                    ->placeholder('contoh: Editor 3D'),

                Textarea::make('description')
                    ->label('Deskripsi')
                    ->rows(2)
                    ->maxLength(255)
                    ->nullable(),
            ])
            ->columns(2);

        // Permission sections per group
        $groupLabels = [
            'user'    => '👤 Manajemen User',
            'task'    => '📋 Manajemen Tugas',
            'role'    => '🛡️ Manajemen Role',
            'review'  => '✅ Review & Submission',
            'general' => '🏠 Umum / Dashboard',
        ];

        foreach ($groupedPermissions as $group => $perms) {
            $label = $groupLabels[$group] ?? ucfirst($group);
            $sections[] = Section::make($label)
                ->schema([
                    CheckboxList::make("permissions_group_{$group}")
                        ->label('')
                        ->options(
                            $perms->pluck('display_name', 'id')->toArray()
                        )
                        ->gridDirection('row')
                        ->columns(3),
                ])
                ->collapsible();
        }

        return $schema->components($sections);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')
                    ->label('Nama Role')
                    ->searchable()
                    ->sortable()
                    ->badge()
                    ->color('warning')
                    ->weight('bold'),

                TextColumn::make('permissions.name')
                    ->label('Permissions (Hak Akses)')
                    ->badge()
                    ->color('info')
                    ->separator(',')
                    ->wrap()
                    ->searchable(),

                TextColumn::make('users_count')
                    ->label('Jumlah User')
                    ->counts('users')
                    ->badge()
                    ->color('success')
                    ->sortable(),

                TextColumn::make('created_at')
                    ->label('Dibuat')
                    ->dateTime('d M Y')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->defaultSort('name')
            ->recordActions([
                EditAction::make()->label('Edit'),
                DeleteAction::make()->label('Hapus'),
            ])
            ->toolbarActions([
                CreateAction::make()->label('+ Tambah Role'),
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index'  => \App\Filament\Resources\RoleResource\Pages\ListRoles::route('/'),
            'create' => \App\Filament\Resources\RoleResource\Pages\CreateRole::route('/create'),
            'edit'   => \App\Filament\Resources\RoleResource\Pages\EditRole::route('/{record}/edit'),
        ];
    }
}
