<?php

namespace App\Filament\Resources\MarketplacePosts;

use App\Filament\Resources\MarketplacePosts\Pages\CreateMarketplacePost;
use App\Filament\Resources\MarketplacePosts\Pages\EditMarketplacePost;
use App\Filament\Resources\MarketplacePosts\Pages\ListMarketplacePosts;
use App\Filament\Resources\MarketplacePosts\Schemas\MarketplacePostForm;
use App\Filament\Resources\MarketplacePosts\Tables\MarketplacePostsTable;
use App\Models\MarketplacePost;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class MarketplacePostResource extends Resource
{
    protected static ?string $model = MarketplacePost::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    protected static ?string $recordTitleAttribute = 'nama_cupang';

    public static function form(Schema $schema): Schema
    {
        return MarketplacePostForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return MarketplacePostsTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListMarketplacePosts::route('/'),
            'create' => CreateMarketplacePost::route('/create'),
            'edit' => EditMarketplacePost::route('/{record}/edit'),
        ];
    }
}
