<?php

namespace App\Filament\Resources\FoodChainResource\Pages;

use App\Filament\Resources\FoodChainResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListFoodChains extends ListRecords
{
    protected static string $resource = FoodChainResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
