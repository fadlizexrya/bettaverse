<?php

namespace App\Filament\Resources\KomentarArtikels;

use App\Filament\Resources\KomentarArtikels\Pages;
use App\Models\KomentarArtikel;
use Filament\Forms;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Tables;
use Filament\Tables\Table;

class KomentarArtikelResource extends Resource
{
    protected static ?string $model = KomentarArtikel::class;

    protected static string | \BackedEnum | null $navigationIcon = 'heroicon-o-chat-bubble-left-right';

    protected static ?string $navigationLabel = 'Artikel Komentar';

    protected static ?string $recordTitleAttribute = 'isi_komentar';

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->schema([
                Forms\Components\Select::make('artikel_id')
                    ->relationship('artikel', 'judul')
                    ->required(),

                Forms\Components\Select::make('user_id')
                    ->relationship('user', 'name')
                    ->required(),

                Forms\Components\Textarea::make('isi_komentar')
                    ->required()
                    ->rows(3),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('artikel.judul')
                    ->label('Artikel')
                    ->searchable(),

                Tables\Columns\TextColumn::make('user.name')
                    ->label('User')
                    ->searchable(),

                Tables\Columns\TextColumn::make('isi_komentar')
                    ->label('Komentar')
                    ->limit(50),
            ]);
            // ❌ HAPUS SEMUA ACTIONS
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListKomentarArtikels::route('/'),
            'create' => Pages\CreateKomentarArtikel::route('/create'),
            'edit' => Pages\EditKomentarArtikel::route('/{record}/edit'),
        ];
    }
}
