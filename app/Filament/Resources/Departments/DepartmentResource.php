<?php

namespace App\Filament\Resources\Departments;

use App\Filament\Resources\Departments\Pages\CreateDepartment;
use App\Filament\Resources\Departments\Pages\EditDepartment;
use App\Filament\Resources\Departments\Pages\ListDepartments;
use App\Filament\Resources\Departments\Pages\ViewDepartment;
use App\Filament\Resources\Departments\Schemas\DepartmentForm;
use App\Filament\Resources\Departments\Tables\DepartmentsTable;
use App\Models\Department;
use BackedEnum;
use Filament\Infolists\Components\TextEntry;
use Filament\Resources\Resource;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use UnitEnum;

class DepartmentResource extends Resource
{
    protected static ?string $model = Department::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedBuildingOffice;

    protected static string|UnitEnum|null $navigationGroup = 'Master Data';

    protected static ?string $recordTitleAttribute = 'name';

    protected static ?int $navigationSort = 0;

    public static function form(Schema $schema): Schema
    {
        return DepartmentForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return DepartmentsTable::configure($table);
    }

    public static function infolist(Schema $schema): Schema
    {
        return $schema->components([
            Section::make('Department')
                ->schema([
                    TextEntry::make('name')->label('Nama'),
                    TextEntry::make('code')->label('Kode')->placeholder('-'),
                    TextEntry::make('slug')->label('Slug')->placeholder('-'),
                    TextEntry::make('is_active')->label('Aktif')->badge()->formatStateUsing(fn ($state) => $state ? 'Aktif' : 'Nonaktif'),
                    TextEntry::make('description')->label('Deskripsi')->placeholder('-')->columnSpanFull(),
                    TextEntry::make('created_at')->label('Dibuat')->dateTime('d M Y, H:i'),
                    TextEntry::make('updated_at')->label('Diperbarui')->dateTime('d M Y, H:i'),
                ])
                ->columns(3),
        ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => ListDepartments::route('/'),
            'create' => CreateDepartment::route('/create'),
            'edit' => EditDepartment::route('/{record}/edit'),
            'view' => ViewDepartment::route('/{record}'),
        ];
    }

    public static function getRelations(): array
    {
        return [];
    }
}
