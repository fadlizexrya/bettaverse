<?php

namespace App\Filament\Widgets;

use App\Models\Artikel;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseWidget;
use Illuminate\Support\HtmlString;

class LatestArtikels extends BaseWidget
{
    protected int | string | array $columnSpan = 'full';

    protected static ?string $heading = 'Artikel Terbaru';

    public function table(Table $table): Table
    {
        return $table
            ->query(
                // Tambahkan select('*') agar yakin semua kolom terambil
                Artikel::query()->select('*')->latest()->limit(5)
            )
            ->columns([
                // PASTIKAN 'judul' ini sama dengan nama kolom di database kamu!
                Tables\Columns\TextColumn::make('judul') 
                    ->label('Judul Artikel')
                    ->placeholder('Judul tidak ditemukan'), // Muncul tulisan ini kalau datanya kosong
                
                Tables\Columns\TextColumn::make('created_at')
                    ->label('Tanggal Rilis')
                    ->dateTime('d M Y'),

                Tables\Columns\TextColumn::make('id')
                    ->label('Aksi')
                    ->formatStateUsing(fn ($state) => new HtmlString("
                        <a href='/admin/artikels/{$state}/edit' 
                           style='background: #fbbf24; color: black; padding: 5px 10px; border-radius: 5px; font-weight: bold; text-decoration: none;'>
                           Sunting
                        </a>
                    ")),
            ])
            ->actions([]);
    }
}