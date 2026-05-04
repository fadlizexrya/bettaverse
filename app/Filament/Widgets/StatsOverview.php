<?php

namespace App\Filament\Widgets;

use App\Models\Artikel; // ✅ FIX: ganti dari Article ke Artikel
use App\Models\User;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class StatsOverview extends BaseWidget
{
    protected function getStats(): array
    {
        return [
            Stat::make('Jumlah Artikel', Artikel::count()) // ✅ FIX DI SINI
                ->description('Artikel yang sudah terbit')
                ->descriptionIcon('heroicon-m-arrow-trending-up')
                ->color('success'),
            
            Stat::make('Total Pengguna', User::count())
                ->description('User terdaftar')
                ->descriptionIcon('heroicon-m-users')
                ->color('info'),
        ];
    }
}