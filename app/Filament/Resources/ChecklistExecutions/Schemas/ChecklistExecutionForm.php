<?php

namespace App\Filament\Resources\ChecklistExecutions\Schemas;

use App\Models\ChecklistTemplate;
use App\Models\Unit;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Radio;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Schemas\Components\Wizard;
use Filament\Schemas\Schema;

class ChecklistExecutionForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Wizard::make([
                    Wizard\Step::make('Informasi Pelaksanaan')
                        ->description('Detail unit, template, dan teknisi.')
                        ->icon('heroicon-o-information-circle')
                        ->schema([
                            Select::make('checklist_template_id')
                                ->label('Template')
                                ->options(ChecklistTemplate::pluck('name', 'id'))
                                ->required()
                                ->disabled()
                                ->dehydrated(),

                            Select::make('unit_id')
                                ->label('Unit')
                                ->options(Unit::pluck('unit_name', 'id'))
                                ->required()
                                ->disabled()
                                ->dehydrated(),

                            Select::make('technician_id')
                                ->label('Teknisi')
                                ->relationship('technician', 'name')
                                ->required()
                                ->disabled()
                                ->dehydrated(),

                            Select::make('status')
                                ->label('Status')
                                ->options([
                                    'draft' => 'Draft',
                                    'submitted' => 'Menunggu Supervisor',
                                    'supervisor_approved' => 'Menunggu Head',
                                    'completed' => 'Selesai',
                                    'rejected' => 'Ditolak',
                                ])
                                ->disabled()
                                ->dehydrated(),
                        ])
                        ->columns(2),

                    Wizard\Step::make('Hasil Pemeriksaan')
                        ->description('Isi jawaban sesuai poin pertanyaan.')
                        ->icon('heroicon-o-clipboard-document-check')
                        ->schema([
                            Repeater::make('answers')
                                ->relationship('answers')
                                ->label('')
                                ->addable(false)
                                ->deletable(false)
                                ->reorderable(false)
                                ->schema([
                                    TextInput::make('question_snapshot')
                                        ->label('Poin Pemeriksaan')
                                        ->disabled()
                                        ->dehydrated(false)
                                        ->columnSpanFull(),

                                    Grid::make(1)
                                        ->schema(function (Get $get) {
                                            $type = $get('type_snapshot');
                                            $required = (bool) $get('is_required_snapshot');
                                            $opts = $get('options_snapshot');

                                            if ($type === 'condition') {
                                                $options = is_array($opts) && count($opts) > 0
                                                    ? array_combine($opts, $opts)
                                                    : ['Baik' => 'Baik', 'Rusak' => 'Rusak', 'Perlu Ganti' => 'Perlu Ganti'];
                                                return [
                                                    Radio::make('answer_text')
                                                        ->label('Pilih Kondisi')
                                                        ->options($options)
                                                        ->inline()
                                                        ->required($required),
                                                ];
                                            }

                                            if ($type === 'yes_no') {
                                                return [
                                                    Radio::make('answer_text')
                                                        ->label('Jawaban')
                                                        ->options(['Ya' => 'Ya', 'Tidak' => 'Tidak'])
                                                        ->inline()
                                                        ->required($required),
                                                ];
                                            }

                                            if ($type === 'number') {
                                                return [
                                                    TextInput::make('answer_text')
                                                        ->label('Nilai Angka')
                                                        ->numeric()
                                                        ->placeholder('Masukkan angka...')
                                                        ->required($required),
                                                ];
                                            }

                                            if ($type === 'text') {
                                                return [
                                                    Textarea::make('answer_text')
                                                        ->label('Keterangan')
                                                        ->rows(3)
                                                        ->placeholder('Ketik temuan...')
                                                        ->required($required),
                                                ];
                                            }

                                            if ($type === 'photo') {
                                                return [
                                                    FileUpload::make('answer_photo_path')
                                                        ->label('Foto Evidence')
                                                        ->image()
                                                        ->directory('checklists/executions')
                                                        ->required($required),
                                                    TextInput::make('answer_text')
                                                        ->label('Keterangan Foto (Opsional)')
                                                        ->placeholder('Keterangan singkat...'),
                                                ];
                                            }

                                            return [
                                                TextInput::make('answer_text')
                                                    ->label('Jawaban')
                                                    ->required($required),
                                            ];
                                        })
                                        ->columnSpanFull(),

                                    Textarea::make('notes')
                                        ->label('Catatan Poin')
                                        ->rows(2)
                                        ->placeholder('Catatan khusus jika ada...')
                                        ->columnSpanFull(),
                                ])
                                ->columns(1)
                                ->columnSpanFull(),
                        ]),

                    Wizard\Step::make('Catatan Akhir')
                        ->description('Ringkasan dan info penolakan.')
                        ->icon('heroicon-o-chat-bubble-bottom-center-text')
                        ->schema([
                            Textarea::make('notes')
                                ->label('Ringkasan Teknisi')
                                ->rows(4)
                                ->placeholder('Ringkasan pekerjaan...')
                                ->columnSpanFull(),
                        ]),
                ])
                ->columnSpanFull()
                ->persistStepInQueryString(),
            ]);
    }
}
