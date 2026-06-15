<?php

namespace App\Filament\Resources\Artikels\Schemas;

use Filament\Schemas\Schema;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Select;

class ArtikelForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema->components([
            TextInput::make('judul')
                ->label('Judul')
                ->required(),

            Textarea::make('isi')
                ->label('Isi Artikel')
                ->required(),

            FileUpload::make('gambar')
                ->label('Gambar')
                ->image()
                ->directory('artikel-images') // 🔥 rapihin folder
                ->nullable(),

            // ✅ TAMBAHAN PENTING (STATUS)
            Select::make('status')
                ->label('Status')
                ->options([
                    'draft' => 'Draft',
                    'published' => 'Published',
                ])
                ->default('draft')
                ->required(),
        ]);
    }
}
