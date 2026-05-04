<?php

namespace App\Filament\Resources\Artikels\Tables;

use Filament\Tables\Table;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\ImageColumn;

// ✅ FIX IMPORT ACTION (WAJIB)
use Filament\Actions\EditAction;
use Filament\Actions\DeleteAction;

class ArtikelsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('judul')
                    ->label('Judul')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('isi')
                    ->label('Isi')
                    ->limit(50),

                ImageColumn::make('gambar')
                    ->label('Gambar')
                    ->square(),

                TextColumn::make('created_at')
                    ->label('Tanggal')
                    ->dateTime('d M Y')
                    ->sortable(),
            ])

            // ✅ FIX ACTIONS DI SINI
            ->actions([
                EditAction::make(),
                DeleteAction::make(),
            ]);
    }
}