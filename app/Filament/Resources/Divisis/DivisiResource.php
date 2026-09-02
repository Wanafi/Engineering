<?php

namespace App\Filament\Resources\Divisis;

use App\Filament\Resources\Divisis\Pages\CreateDivisi;
use App\Filament\Resources\Divisis\Pages\EditDivisi;
use App\Filament\Resources\Divisis\Pages\ListDivisis;
use App\Filament\Resources\Divisis\Pages\ViewDivisi;
use App\Filament\Resources\Divisis\Schemas\DivisiForm;
use App\Filament\Resources\Divisis\Tables\DivisisTable;
use App\Models\Divisi;
use BackedEnum;
use UnitEnum;
use Filament\Infolists\Components\ColorEntry;
use Filament\Infolists\Components\IconEntry;
use Filament\Infolists\Components\TextEntry;
use Filament\Resources\Resource;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class DivisiResource extends Resource
{
    protected static ?string $model = Divisi::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedUserGroup;

    protected static string|UnitEnum|null $navigationGroup = 'Human Resource';

    protected static ?string $recordTitleAttribute = 'Divisi';

    public static function form(Schema $schema): Schema
    {
        return DivisiForm::configure($schema);
    }

    public static function infolist(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Informasi Divisi')
                    ->icon('heroicon-o-user-group')
                    ->schema([
                        TextEntry::make('nama_divisi')
                            ->label('Nama Divisi'),

                        ColorEntry::make('warna')
                            ->label('Warna Indikator'),

                        IconEntry::make('is_active')
                            ->label('Aktif')
                            ->boolean(),

                        TextEntry::make('slug')
                            ->label('Slug')
                            ->placeholder('-'),

                        TextEntry::make('created_at')
                            ->label('Dibuat')
                            ->dateTime('d M Y, H:i'),

                        TextEntry::make('updated_at')
                            ->label('Diperbarui')
                            ->dateTime('d M Y, H:i'),
                    ])
                    ->columns(3),
            ]);
    }

    public static function table(Table $table): Table
    {
        return DivisisTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListDivisis::route('/'),
            'create' => CreateDivisi::route('/create'),
            'edit' => EditDivisi::route('/{record}/edit'),
            'view' => ViewDivisi::route('/{record}'),
        ];
    }
}
