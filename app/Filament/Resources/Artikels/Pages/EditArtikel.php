<?php

namespace App\Filament\Resources\Artikels\Pages;

use Filament\Resources\Pages\EditRecord;
use App\Filament\Resources\Artikels\ArtikelResource;

class EditArtikel extends EditRecord
{
    protected static string $resource = ArtikelResource::class;

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}