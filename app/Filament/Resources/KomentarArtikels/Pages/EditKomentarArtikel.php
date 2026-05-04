<?php

namespace App\Filament\Resources\KomentarArtikels\Pages;

use App\Filament\Resources\KomentarArtikels\KomentarArtikelResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditKomentarArtikel extends EditRecord
{
    protected static string $resource = KomentarArtikelResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
