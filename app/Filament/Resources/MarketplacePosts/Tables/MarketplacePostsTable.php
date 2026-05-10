<?php

namespace App\Filament\Resources\MarketplacePosts\Tables;

use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class MarketplacePostsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('judul')
                    ->searchable(),

                TextColumn::make('seller.name')
                    ->label('Penjual'),

                TextColumn::make('harga')
                    ->money('IDR', locale: 'id_ID'),

                TextColumn::make('stok')
                    ->badge()
                    ->color(fn ($state) => $state < 5 ? 'danger' : 'success'),

                TextColumn::make('contacts_count')
                    ->counts('contacts')
                    ->label('Dihubungi'),
            ]);
    }
}
