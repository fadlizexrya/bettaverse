<?php

namespace App\Filament\Widgets;

use Filament\Widgets\ChartWidget;

class ArtikelChart extends ChartWidget
{
    protected ?string $heading = 'Artikel Chart';

   protected function getData(): array
{
    return [
        'datasets' => [
            [
                'label' => 'Artikel Dibuat',
                'data' => [10, 25, 15, 30, 45, 20, 35], // Nanti ini bisa diambil dari database
                'backgroundColor' => '#fbbf24', // Warna kuning biar masuk sama tema gelapmu
                'borderColor' => '#f59e0b',
            ],
        ],
        'labels' => ['Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun', 'Jul'],
    ];
}

    protected function getType(): string
    {
        return 'bar';
    }
}
