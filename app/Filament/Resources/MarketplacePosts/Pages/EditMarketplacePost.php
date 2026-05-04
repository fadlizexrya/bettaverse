<?php

namespace App\Filament\Resources\MarketplacePosts\Pages;

use App\Filament\Resources\MarketplacePosts\MarketplacePostResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditMarketplacePost extends EditRecord
{
    protected static string $resource = MarketplacePostResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
