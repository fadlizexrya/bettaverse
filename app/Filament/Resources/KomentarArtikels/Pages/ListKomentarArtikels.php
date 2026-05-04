<?php

namespace App\Filament\Resources\KomentarArtikels\Pages;

use App\Filament\Resources\KomentarArtikels\KomentarArtikelResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListKomentarArtikels extends ListRecords
{
    protected static string $resource = KomentarArtikelResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
