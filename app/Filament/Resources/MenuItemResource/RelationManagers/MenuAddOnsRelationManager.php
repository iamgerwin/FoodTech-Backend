<?php

namespace App\Filament\Resources\MenuItemResource\RelationManagers;

use Filament\Forms;
use Filament\Tables;
use Filament\Resources\RelationManagers\RelationManager;

class MenuAddOnsRelationManager extends RelationManager
{
    protected static string $relationship = 'menuAddOns';
    protected static ?string $recordTitleAttribute = 'name';

    public function form(Forms\Form $form): Forms\Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('name')
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('price')
                    ->numeric()
                    ->required(),
                Forms\Components\Toggle::make('is_available'),
                Forms\Components\TextInput::make('sort_order')
                    ->numeric(),
            ]);
    }

    public function table(Tables\Table $table): Tables\Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name'),
                Tables\Columns\TextColumn::make('price')->money('PHP'),
                Tables\Columns\IconColumn::make('is_available')->boolean(),
                Tables\Columns\TextColumn::make('sort_order'),
            ])
            ->filters([
                // Add filters if needed
            ]);
    }
}
