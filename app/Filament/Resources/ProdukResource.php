<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ProdukResource\Pages;
use App\Models\Produk;
use Filament\Forms;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Actions\EditAction;
use Filament\Actions\DeleteAction;

class ProdukResource extends Resource
{
    protected static ?string $model = Produk::class;
    protected static ?string $navigationLabel = 'Marketplace';
    protected static ?string $pluralModelLabel = 'Marketplace';
    protected static ?string $modelLabel = 'Produk';

    public static function form(Schema $form): Schema
    {
        return $form->components([
            Forms\Components\TextInput::make('nama')
                ->label('Nama Produk')
                ->required(),
            Forms\Components\Textarea::make('deskripsi')
                ->label('Deskripsi')
                ->rows(4),
            Forms\Components\TextInput::make('harga')
                ->label('Harga')
                ->numeric()
                ->prefix('Rp')
                ->required(),
            Forms\Components\TextInput::make('stok')
                ->label('Stok')
                ->numeric()
                ->default(0)
                ->required(),
            Forms\Components\TextInput::make('gambar')
                ->label('URL Gambar'),
            Forms\Components\Select::make('user_id')
                ->label('Penjual')
                ->relationship('user', 'name')
                ->searchable()
                ->required(),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\ImageColumn::make('gambar')
                    ->label('Gambar')
                    ->circular(),
                Tables\Columns\TextColumn::make('nama')
                    ->label('Nama Produk')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('harga')
                    ->label('Harga')
                    ->money('IDR')
                    ->sortable(),
                Tables\Columns\TextColumn::make('stok')
                    ->label('Stok')
                    ->sortable()
                    ->badge()
                    ->color(fn ($state) => $state > 0 ? 'success' : 'danger'),
                Tables\Columns\TextColumn::make('user.name')
                    ->label('Penjual')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('created_at')
                    ->label('Diposting')
                    ->dateTime('d M Y')
                    ->sortable(),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('user_id')
                    ->label('Filter Penjual')
                    ->relationship('user', 'name'),
            ])
            ->recordActions([
    \Filament\Actions\EditAction::make(),
    \Filament\Actions\DeleteAction::make(),
])
->bulkActions([
    \Filament\Actions\DeleteBulkAction::make(),
]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListProduks::route('/'),
            'create' => Pages\CreateProduk::route('/create'),
            'edit' => Pages\EditProduk::route('/{record}/edit'),
        ];
    }
}