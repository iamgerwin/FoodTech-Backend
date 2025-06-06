<?php

namespace App\Filament\Resources\FoodChainResource\Pages;

use App\Filament\Resources\FoodChainResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditFoodChain extends EditRecord
{
    protected static string $resource = FoodChainResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
