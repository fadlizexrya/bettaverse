<?php

namespace App\Filament\Resources\KomentarArtikels\Schemas;

use Filament\Schemas\Schema;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Hidden;
use Illuminate\Support\Facades\Auth;

class KomentarArtikelForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema->components([

            Select::make('artikel_id')
                ->label('Artikel')
                ->relationship('artikel', 'judul')
                ->searchable()
                ->required(),

            Textarea::make('isi_komentar')
                ->label('Isi Komentar')
                ->required(),

            Hidden::make('user_id')
                ->default(fn () => Auth::id()),
        ]);
    }
}