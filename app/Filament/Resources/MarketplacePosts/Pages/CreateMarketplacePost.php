<?php

namespace App\Filament\Resources\MarketplacePosts\Pages;

use App\Filament\Resources\MarketplacePosts\MarketplacePostResource;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Support\Facades\Auth;

class CreateMarketplacePost extends CreateRecord
{
    protected static string $resource = MarketplacePostResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
{
    $data['user_id'] = Auth::id();
    return $data;
}
}