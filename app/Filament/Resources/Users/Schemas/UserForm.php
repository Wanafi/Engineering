<?php

namespace App\Filament\Resources\Users\Schemas;

use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Illuminate\Support\Facades\Hash;

class UserForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Informasi Pengguna')
                    ->description('Data identitas pengguna.')
                    ->icon('heroicon-o-user')
                    ->schema([
                        TextInput::make('name')
                            ->label('Nama Lengkap')
                            ->required()
                            ->maxLength(255)
                            ->placeholder('Nama lengkap'),

                        TextInput::make('email')
                            ->label('Email')
                            ->email()
                            ->required()
                            ->unique(ignoreRecord: true)
                            ->maxLength(255),

                        TextInput::make('phone')
                            ->label('No. HP')
                            ->tel()
                            ->maxLength(20)
                            ->columnSpanFull(),
                    ])
                    ->columns(2),

                Section::make('Keamanan Akun')
                    ->description('Kosongkan jika tidak ingin mengubah password.')
                    ->icon('heroicon-o-lock-closed')
                    ->schema([
                        TextInput::make('password')
                            ->label('Password')
                            ->password()
                            ->revealable()
                            ->dehydrateStateUsing(fn ($state) => filled($state) ? Hash::make($state) : null)
                            ->dehydrated(fn ($state) => filled($state))
                            ->required(fn (string $operation): bool => $operation === 'create')
                            ->minLength(8)
                            ->maxLength(255),

                        TextInput::make('password_confirmation')
                            ->label('Konfirmasi Password')
                            ->password()
                            ->revealable()
                            ->same('password')
                            ->required(fn (string $operation): bool => $operation === 'create')
                            ->dehydrated(false),
                    ])
                    ->columns(2),

                Section::make('Penempatan & Hak Akses')
                    ->description('Divisi, status kepegawaian, dan role sistem.')
                    ->icon('heroicon-o-shield-check')
                    ->schema([
                        Select::make('division_id')
                            ->label('Divisi')
                            ->relationship('division', 'nama_divisi')
                            ->searchable()
                            ->preload()
                            ->native(false)
                            ->required(),

                        Select::make('status')
                            ->label('Status Pegawai')
                            ->options([
                                'tetap' => 'Tetap',
                                'kontrak' => 'Kontrak',
                                'magang' => 'Magang',
                                'lepas' => 'Freelance',
                            ])
                            ->required()
                            ->native(false)
                            ->default('tetap'),

                        Select::make('roles')
                            ->label('Role Sistem')
                            ->relationship('roles', 'name')
                            ->multiple()
                            ->preload()
                            ->searchable()
                            ->required()
                            ->native(false)
                            ->columnSpanFull(),
                    ])
                    ->columns(2),
            ]);
    }
}
