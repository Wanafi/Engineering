<?php

namespace App\Filament\Resources\ChecklistTemplates;

use App\Filament\Resources\ChecklistTemplates\Pages\CreateChecklistTemplate;
use App\Filament\Resources\ChecklistTemplates\Pages\EditChecklistTemplate;
use App\Filament\Resources\ChecklistTemplates\Pages\ListChecklistTemplates;
use App\Filament\Resources\ChecklistTemplates\Pages\ViewChecklistTemplate;
use App\Filament\Resources\ChecklistTemplates\Schemas\ChecklistTemplateForm;
use App\Filament\Resources\ChecklistTemplates\Tables\ChecklistTemplatesTable;
use App\Models\ChecklistTemplate;
use BackedEnum;
use Filament\Infolists\Components\IconEntry;
use Filament\Infolists\Components\RepeatableEntry;
use Filament\Infolists\Components\TextEntry;
use Filament\Resources\Resource;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use UnitEnum;

class ChecklistTemplateResource extends Resource
{
    protected static ?string $model = ChecklistTemplate::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedClipboardDocumentCheck;

    protected static string|UnitEnum|null $navigationGroup = 'Checklist Rutin';

    protected static ?string $modelLabel = 'Template Checklist';

    protected static ?string $pluralModelLabel = 'Template Checklist';

    protected static ?string $recordTitleAttribute = 'name';

    public static function form(Schema $schema): Schema
    {
        return ChecklistTemplateForm::configure($schema);
    }

    public static function infolist(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Informasi Template')
                    ->description('Detail master template dan pertanyaan.')
                    ->icon('heroicon-o-clipboard-document-check')
                    ->schema([
                        TextEntry::make('name')
                            ->label('Nama Template'),

                        TextEntry::make('division.nama_divisi')
                            ->label('Divisi')
                            ->placeholder('Semua Divisi (Umum)'),

                        IconEntry::make('is_active')
                            ->label('Status Aktif')
                            ->boolean(),

                        TextEntry::make('description')
                            ->label('Deskripsi')
                            ->placeholder('-')
                            ->columnSpanFull(),
                    ])
                    ->columns(3),

                Section::make('Daftar Pertanyaan')
                    ->icon('heroicon-o-list-bullet')
                    ->schema([
                        RepeatableEntry::make('questions')
                            ->label('')
                            ->schema([
                                TextEntry::make('label')
                                    ->label('Pertanyaan')
                                    ->weight('bold')
                                    ->columnSpan(2),

                                TextEntry::make('type')
                                    ->label('Tipe')
                                    ->badge(),

                                TextEntry::make('is_required')
                                    ->label('Wajib')
                                    ->boolean(),
                            ])
                            ->columns(4)
                            ->columnSpanFull(),
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return ChecklistTemplatesTable::configure($table);
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
            'index' => ListChecklistTemplates::route('/'),
            'create' => CreateChecklistTemplate::route('/create'),
            'edit' => EditChecklistTemplate::route('/{record}/edit'),
            'view' => ViewChecklistTemplate::route('/{record}'),
        ];
    }
}
