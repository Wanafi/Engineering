<?php

namespace App\Filament\Resources\Units;

use App\Filament\Resources\Units\Pages\CreateUnit;
use App\Filament\Resources\Units\Pages\EditUnit;
use App\Filament\Resources\Units\Pages\ListUnits;
use App\Filament\Resources\Units\Pages\ViewUnit;
use App\Filament\Resources\Units\Schemas\UnitForm;
use App\Filament\Resources\Units\Tables\UnitsTable;
use App\Models\Unit;
use Filament\Infolists\Components\TextEntry;
use Filament\Resources\Resource;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Filament\Tables\Table;

class UnitResource extends Resource
{
    protected static ?string $model = Unit::class;

    protected static ?string $recordTitleAttribute = 'unit_name';

    protected static ?string $navigationLabel = 'Units';

    protected static ?string $modelLabel = 'Unit';

    protected static ?string $pluralModelLabel = 'Units';

    protected static string|\BackedEnum|null $navigationIcon = 'heroicon-o-building-office-2';

    public static function form(Schema $schema): Schema
    {
        return UnitForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return UnitsTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [];
    }

    public static function infolist(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Identitas Unit')
                    ->icon('heroicon-o-building-office-2')
                    ->schema([
                        TextEntry::make('unit_code')->label('Kode Unit'),
                        TextEntry::make('unit_name')->label('Nama Unit'),
                        TextEntry::make('divisis.nama_divisi')->label('Divisi'),
                        TextEntry::make('status')->label('Status')->badge(),
                    ])
                    ->columns(2),

                Section::make('Lokasi Penempatan')
                    ->icon('heroicon-o-map-pin')
                    ->schema([
                        TextEntry::make('location')->label('Lokasi')->placeholder('-'),
                        TextEntry::make('floor')->label('Lantai')->placeholder('-'),
                        TextEntry::make('area')->label('Area')->placeholder('-'),
                    ])
                    ->columns(3),

                Section::make('Keterangan')
                    ->schema([
                        TextEntry::make('description')->label('Deskripsi')->placeholder('-')->columnSpanFull(),
                        TextEntry::make('created_at')->label('Dibuat')->dateTime('d M Y, H:i'),
                        TextEntry::make('updated_at')->label('Diperbarui')->dateTime('d M Y, H:i'),
                    ])
                    ->columns(2),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => ListUnits::route('/'),
            'create' => CreateUnit::route('/create'),
            'edit' => EditUnit::route('/{record}/edit'),
            'view' => ViewUnit::route('/{record}'),
        ];
    }
}