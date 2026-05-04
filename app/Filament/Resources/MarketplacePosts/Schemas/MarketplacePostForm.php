<?php

namespace App\Filament\Resources\MarketplacePosts\Schemas;

use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Schemas\Schema;
use Illuminate\Support\Facades\Auth;

class MarketplacePostForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([

                // ✅ tampilkan nama penjual (hanya display)
                TextInput::make('penjual')
                    ->label('Penjual')
                    ->default(fn () => Auth::user()?->name)
                    ->disabled()
                    ->dehydrated(false), // ⛔ tidak ikut ke database

                // ✅ ini yang disimpan ke DB
                TextInput::make('user_id')
                    ->default(fn () => Auth::id())
                    ->hidden(),

                TextInput::make('judul')
                    ->required(),

                Textarea::make('deskripsi')
                    ->required(),

                TextInput::make('harga')
                    ->numeric()
                    ->required(),

                TextInput::make('stok')
                    ->numeric()
                    ->required(),
            ]);
    }
}