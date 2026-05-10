<?php

namespace App\Filament\Resources\ArtikelResource\RelationManagers;

use Filament\Resources\RelationManagers\RelationManager;
use Filament\Schemas\Schema;
use Filament\Tables\Table;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Select;
use Filament\Tables\Columns\TextColumn;

class KomentarRelationManager extends RelationManager
{
    protected static string $relationship = 'komentar';

    protected static ?string $recordTitleAttribute = 'komentar';

    public function form(Schema $schema): Schema
    {
        return $schema->components([
            Textarea::make('komentar')
                ->label('Isi Komentar')
                ->required(),

            Select::make('user_id')
                ->label('User')
                ->relationship('user', 'name')
                ->required(),
        ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('komentar')->limit(50),
                TextColumn::make('user.name')->label('User'),
                TextColumn::make('created_at')->dateTime(),
            ]);
    }
}