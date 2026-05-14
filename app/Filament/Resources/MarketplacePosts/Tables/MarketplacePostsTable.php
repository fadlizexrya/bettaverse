<?php

namespace App\Filament\Resources\MarketplacePosts\Tables;

use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\ImageColumn; // Tambahkan ini kalau mau munculkan foto
use Filament\Tables\Table;

class MarketplacePostsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                // 1. Tampilkan Foto Ikan (biar makin keren dashboard-nya)
                ImageColumn::make('foto_cupang')
                    ->label('Foto')
                    ->disk('public')
                    ->circular(),

                // 2. Ganti 'judul' jadi 'nama_cupang' (Fix kolom kosong)
                TextColumn::make('nama_cupang')
                    ->label('Nama Ikan')
                    ->searchable()
                    ->sortable(),

                // 3. Tambahkan Jenis Cupang
                TextColumn::make('jenis_cupang')
                    ->label('Jenis')
                    ->badge(),

                // 4. Tambahkan Nomor Telepon / WA (Ini yang kamu minta)
                TextColumn::make('no_wa')
                    ->label('No. WhatsApp')
                    ->copyable() // User bisa klik untuk copy nomernya
                    ->searchable(),

                // 5. Harga dengan format Rupiah
                TextColumn::make('harga')
                    ->label('Harga')
                    ->money('IDR')
                    ->sortable(),

                // 6. Stok
                TextColumn::make('stok')
                    ->label('Stok')
                    ->numeric()
                    ->sortable(),
            ]);
    }
}
