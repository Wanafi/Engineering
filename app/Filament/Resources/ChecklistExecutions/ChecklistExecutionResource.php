<?php

namespace App\Filament\Resources\ChecklistExecutions;

use App\Filament\Resources\ChecklistExecutions\Pages\EditChecklistExecution;
use App\Filament\Resources\ChecklistExecutions\Pages\ListChecklistExecutions;
use App\Filament\Resources\ChecklistExecutions\Pages\ViewChecklistExecution;
use App\Filament\Resources\ChecklistExecutions\Schemas\ChecklistExecutionForm;
use App\Filament\Resources\ChecklistExecutions\Tables\ChecklistExecutionsTable;
use App\Models\ChecklistExecution;
use BackedEnum;
use Filament\Infolists\Components\ImageEntry;
use Filament\Infolists\Components\RepeatableEntry;
use Filament\Infolists\Components\TextEntry;
use Filament\Resources\Resource;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use UnitEnum;

class ChecklistExecutionResource extends Resource
{
    protected static ?string $model = ChecklistExecution::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedClipboardDocumentList;

    protected static string|UnitEnum|null $navigationGroup = 'Operations';

    protected static ?int $navigationSort = 4;

    protected static ?string $modelLabel = 'Pelaksanaan Checklist';

    protected static ?string $pluralModelLabel = 'Pelaksanaan Checklist';

    protected static ?string $recordTitleAttribute = 'id';

    public static function form(Schema $schema): Schema
    {
        return ChecklistExecutionForm::configure($schema);
    }

    public static function infolist(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Informasi Pelaksanaan')
                    ->description('Detail unit, template, dan status.')
                    ->icon('heroicon-o-information-circle')
                    ->schema([
                        TextEntry::make('template.name')
                            ->label('Template Checklist'),

                        TextEntry::make('unit.unit_name')
                            ->label('Unit / Equipment'),

                        TextEntry::make('technician.name')
                            ->label('Teknisi Pelaksana'),

                        TextEntry::make('status')
                            ->label('Status')
                            ->badge()
                            ->color(fn (string $state): string => match ($state) {
                                'draft' => 'gray',
                                'submitted' => 'info',
                                'supervisor_approved', 'completed' => 'success',
                                'rejected' => 'danger',
                                default => 'gray',
                            }),

                        TextEntry::make('created_at')
                            ->label('Waktu Dibuat')
                            ->dateTime('d M Y, H:i'),

                        TextEntry::make('submitted_at')
                            ->label('Waktu Submit')
                            ->dateTime('d M Y, H:i')
                            ->placeholder('-'),
                    ])
                    ->columns(2),

                Section::make('Hasil Pemeriksaan')
                    ->description('Jawaban dari teknisi.')
                    ->icon('heroicon-o-clipboard-document-check')
                    ->schema([
                        RepeatableEntry::make('answers')
                            ->label('')
                            ->schema([
                                TextEntry::make('question_snapshot')
                                    ->label('Poin Pertanyaan')
                                    ->weight('bold')
                                    ->columnSpanFull(),

                                TextEntry::make('type_snapshot')
                                    ->label('Tipe')
                                    ->badge(),

                                TextEntry::make('answer_text')
                                    ->label('Jawaban')
                                    ->placeholder('-'),

                                ImageEntry::make('answer_photo_path')
                                    ->label('Foto Bukti')
                                    ->disk('public')
                                    ->visible(fn ($record) => filled($record?->answer_photo_path))
                                    ->columnSpanFull(),

                                TextEntry::make('notes')
                                    ->label('Catatan Item')
                                    ->placeholder('-')
                                    ->columnSpanFull(),
                            ])
                            ->columns(2)
                            ->columnSpanFull(),
                    ]),

                Section::make('Riwayat Approval & Catatan')
                    ->icon('heroicon-o-shield-check')
                    ->schema([
                        TextEntry::make('notes')
                            ->label('Ringkasan Catatan Teknisi')
                            ->placeholder('-')
                            ->columnSpanFull(),

                        TextEntry::make('supervisor.name')
                            ->label('Disetujui Supervisor')
                            ->placeholder('-'),

                        TextEntry::make('supervisor_approved_at')
                            ->label('Waktu Approve Supervisor')
                            ->dateTime('d M Y, H:i')
                            ->placeholder('-'),

                        TextEntry::make('head.name')
                            ->label('Disetujui Head (Final)')
                            ->placeholder('-'),

                        TextEntry::make('head_approved_at')
                            ->label('Waktu Approve Head')
                            ->dateTime('d M Y, H:i')
                            ->placeholder('-'),

                        TextEntry::make('rejection_reason')
                            ->label('Alasan Penolakan')
                            ->color('danger')
                            ->placeholder('-')
                            ->columnSpanFull()
                            ->visible(fn ($record) => filled($record?->rejection_reason)),
                    ])
                    ->columns(2),
            ]);
    }

    public static function table(Table $table): Table
    {
        return ChecklistExecutionsTable::configure($table);
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
            'index' => ListChecklistExecutions::route('/'),
            'edit' => EditChecklistExecution::route('/{record}/edit'),
            'view' => ViewChecklistExecution::route('/{record}'),
        ];
    }
}
