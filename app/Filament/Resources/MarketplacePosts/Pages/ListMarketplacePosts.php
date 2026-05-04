<?php

namespace App\Filament\Resources\MarketplacePosts\Pages;

use App\Filament\Resources\MarketplacePosts\MarketplacePostResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListMarketplacePosts extends ListRecords
{
    protected static string $resource = MarketplacePostResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
