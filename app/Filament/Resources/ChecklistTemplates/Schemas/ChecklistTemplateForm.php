<?php

namespace App\Filament\Resources\ChecklistTemplates\Schemas;

use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TagsInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Wizard;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Schemas\Schema;

class ChecklistTemplateForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Wizard::make([
                    Wizard\Step::make('Informasi Template')
                        ->description('Nama, divisi, dan status template.')
                        ->icon('heroicon-o-clipboard-document-list')
                        ->schema([
                            TextInput::make('name')
                                ->label('Nama Template')
                                ->placeholder('Contoh: PM Escalator Harian')
                                ->required()
                                ->maxLength(255),

                            Select::make('division_id')
                                ->label('Divisi')
                                ->relationship('division', 'nama_divisi')
                                ->searchable()
                                ->preload()
                                ->native(false)
                                ->placeholder('Semua Divisi (Umum)')
                                ->helperText('Kosongkan jika berlaku semua divisi.'),

                            Toggle::make('is_active')
                                ->label('Aktif')
                                ->default(true)
                                ->helperText('Template aktif wajib punya minimal 1 pertanyaan.'),

                            Textarea::make('description')
                                ->label('Deskripsi')
                                ->placeholder('Keterangan template...')
                                ->rows(3)
                                ->columnSpanFull(),
                        ])
                        ->columns(2),

                    Wizard\Step::make('Daftar Pertanyaan')
                        ->description('Item pemeriksaan untuk template ini.')
                        ->icon('heroicon-o-list-bullet')
                        ->schema([
                            Repeater::make('questions')
                                ->relationship('questions')
                                ->label('')
                                ->schema([
                                    TextInput::make('label')
                                        ->label('Pertanyaan')
                                        ->placeholder('Contoh: Kondisi handrail')
                                        ->required()
                                        ->maxLength(255)
                                        ->columnSpan(2),

                                    Select::make('type')
                                        ->label('Tipe Jawaban')
                                        ->options([
                                            'condition' => 'Kondisi',
                                            'yes_no' => 'Ya / Tidak',
                                            'text' => 'Teks',
                                            'number' => 'Angka',
                                            'photo' => 'Foto',
                                        ])
                                        ->required()
                                        ->default('condition')
                                        ->live()
                                        ->native(false)
                                        ->columnSpan(2),

                                    TagsInput::make('options')
                                        ->label('Pilihan Opsi')
                                        ->placeholder('Baik, Rusak, Perlu Ganti')
                                        ->helperText('Wajib jika tipe Kondisi.')
                                        ->required(fn (Get $get): bool => $get('type') === 'condition')
                                        ->visible(fn (Get $get): bool => $get('type') === 'condition')
                                        ->columnSpanFull(),

                                    Toggle::make('is_required')
                                        ->label('Wajib Diisi')
                                        ->default(true),

                                    TextInput::make('order')
                                        ->label('Urutan')
                                        ->numeric()
                                        ->default(0)
                                        ->required(),
                                ])
                                ->columns(4)
                                ->defaultItems(1)
                                ->reorderable()
                                ->orderColumn('order')
                                ->addActionLabel('Tambah Pertanyaan')
                                ->columnSpanFull()
                                ->required()
                                ->rule(function () {
                                    return function (string $attribute, $value, $fail) {
                                        if (empty($value) || !is_array($value) || count($value) === 0) {
                                            $fail('Minimal 1 pertanyaan.');
                                        }
                                    };
                                }),
                        ]),
                ])
                ->columnSpanFull()
                ->persistStepInQueryString(),
            ]);
    }
}
