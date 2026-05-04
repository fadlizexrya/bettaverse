<?php

namespace App\Filament\Resources\Artikels\Pages;

use Filament\Resources\Pages\CreateRecord;
use App\Filament\Resources\Artikels\ArtikelResource;

class CreateArtikel extends CreateRecord
{
    protected static string $resource = ArtikelResource::class;

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}