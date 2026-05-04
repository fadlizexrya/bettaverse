<?php

namespace App\Filament\Resources\Artikels;

use BackedEnum;
use App\Models\Artikel;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Tables\Table;
use App\Filament\Resources\Artikels\Pages\CreateArtikel;
use App\Filament\Resources\Artikels\Pages\EditArtikel;
use App\Filament\Resources\Artikels\Pages\ListArtikels;
use App\Filament\Resources\Artikels\Schemas\ArtikelForm;
use App\Filament\Resources\Artikels\Tables\ArtikelsTable;
use App\Filament\Resources\Artikels\RelationManagers\KomentarRelationManager;

class ArtikelResource extends Resource
{
    protected static ?string $model = Artikel::class;

    // ✅ FIX ERROR DI SINI
    protected static string | BackedEnum | null $navigationIcon = 'heroicon-o-rectangle-stack';

    protected static ?string $recordTitleAttribute = 'judul';

    // FORM
    public static function form(Schema $schema): Schema
    {
        return ArtikelForm::configure($schema);
    }

    // TABLE
    public static function table(Table $table): Table
    {
        return ArtikelsTable::configure($table);
    }

    // RELATION MANAGER
   public static function getRelations(): array
{
    return [];
}

    // PAGES
    public static function getPages(): array
    {
        return [
            'index' => ListArtikels::route('/'),
            'create' => CreateArtikel::route('/create'),
            'edit' => EditArtikel::route('/{record}/edit'),
        ];
    }
}