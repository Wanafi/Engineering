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

                // ========================================
                // INFORMASI PENGGUNA
                // ========================================
                Section::make('Informasi Pengguna')
                    ->description('Informasi dasar akun pengguna.')
                    ->icon('heroicon-o-user')
                    ->schema([
                        TextInput::make('name')
                            ->label('Nama Lengkap')
                            ->placeholder('Masukkan nama lengkap')
                            ->required()
                            ->maxLength(255),

                        TextInput::make('email')
                            ->label('Alamat Email')
                            ->placeholder('contoh@email.com')
                            ->email()
                            ->required()
                            ->unique(ignoreRecord: true)
                            ->maxLength(255),

                        TextInput::make('phone')
                            ->label('No. Telepon/HP')
                            ->tel()
                            ->maxLength(20),
                    ])
                    ->columns(2),

                // ========================================
                // KEAMANAN AKUN
                // ========================================
                Section::make('Keamanan Akun')
                    ->description('Atur password untuk akun pengguna.')
                    ->icon('heroicon-o-lock-closed')
                    ->schema([
                        TextInput::make('password')
                            ->label('Password')
                            ->placeholder('Masukkan password')
                            ->password()
                            ->revealable()
                            ->dehydrateStateUsing(
                                fn($state) => filled($state)
                                    ? Hash::make($state)
                                    : null
                            )
                            ->dehydrated(fn($state) => filled($state))
                            ->required(
                                fn(string $operation): bool =>
                                $operation === 'create'
                            )
                            ->minLength(8)
                            ->maxLength(255),

                        TextInput::make('password_confirmation')
                            ->label('Konfirmasi Password')
                            ->placeholder('Ulangi password')
                            ->password()
                            ->revealable()
                            ->same('password')
                            ->required(
                                fn(string $operation): bool =>
                                $operation === 'create'
                            )
                            ->dehydrated(false),
                    ])
                    ->columns(2),

                // ========================================
                // HAK AKSES
                // ========================================
                Section::make('Hak Akses')
                    ->description('Tentukan role dan hak akses pengguna.')
                    ->icon('heroicon-o-shield-check')
                    ->schema([
                        Select::make('division_id')
                            ->label('Divisi')
                            ->relationship('division', 'nama_divisi')
                            ->searchable()
                            ->preload()
                            ->required(),

                        Select::make('status')
                            ->label('Status Kepegawaian')
                            ->options([
                                'tetap' => 'Tetap',
                                'kontrak' => 'Kontrak',
                                'magang' => 'Magang',
                                'lepas' => 'Lepas/Freelance',
                            ])
                            ->required()
                            ->default('tetap'),
                        Select::make('roles')
                            ->label('Role')
                            ->relationship('roles', 'name')
                            ->multiple()
                            ->preload()
                            ->searchable()
                            ->required()
                            ->native(false),
                    ]),
            ]);
    }
}
