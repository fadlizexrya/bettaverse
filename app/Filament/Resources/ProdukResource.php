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
            // Sesuaikan form input agar sinkron dengan DB
            Forms\Components\TextInput::make('nama_cupang')
                ->label('Nama Ikan')
                ->required(),
            Forms\Components\TextInput::make('jenis_cupang')
                ->label('Jenis Cupang')
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
            Forms\Components\FileUpload::make('foto_cupang')
                ->label('Upload Foto Ikan')
                ->image()
                ->directory('produk-images')
                ->disk('public'),
            Forms\Components\TextInput::make('no_wa')
                ->label('Nomor WhatsApp')
                ->placeholder('08xxx')
                ->required(),
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
                // 1. Perbaiki kolom Gambar (panggil foto_cupang)
                Tables\Columns\ImageColumn::make('foto_cupang')
                    ->label('Gambar')
                    ->disk('public')
                    ->circular(),

                // 2. Perbaiki kolom Nama (panggil nama_cupang)
                Tables\Columns\TextColumn::make('nama_cupang')
                    ->label('Nama Produk')
                    ->searchable()
                    ->sortable(),

                // 3. Tambahkan No WA biar lengkap
                Tables\Columns\TextColumn::make('no_wa')
                    ->label('WhatsApp')
                    ->searchable(),

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
