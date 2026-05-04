<?php

namespace App\Filament\Resources\Artikels\Pages;

use Filament\Resources\Pages\ListRecords;
use App\Filament\Resources\Artikels\ArtikelResource;
use Filament\Actions\CreateAction;
use Filament\Actions\Action;

class ListArtikels extends ListRecords
{
    protected static string $resource = ArtikelResource::class;

    protected function getHeaderActions(): array
    {
        return [
            // ➕ CREATE (utama)
            CreateAction::make()
                ->label('Tambah Artikel')
                ->icon('heroicon-o-plus'),

            // 🔄 REFRESH DATA
            Action::make('refresh')
                ->label('Refresh')
                ->icon('heroicon-o-arrow-path')
                ->action(fn () => $this->redirect(request()->header('Referer'))),

            // 📊 QUICK STATS (simple alert)
            Action::make('info')
                ->label('Info')
                ->icon('heroicon-o-information-circle')
                ->action(fn () => \Filament\Notifications\Notification::make()
                    ->title('Data Artikel')
                    ->body('Total artikel saat ini: ' . \App\Models\Artikel::count())
                    ->success()
                    ->send()
                ),
        ];
    }
}