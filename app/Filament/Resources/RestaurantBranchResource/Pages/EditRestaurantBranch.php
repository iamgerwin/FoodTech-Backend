<?php

namespace App\Filament\Resources\RestaurantBranchResource\Pages;

use App\Filament\Resources\RestaurantBranchResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditRestaurantBranch extends EditRecord
{
    protected static string $resource = RestaurantBranchResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
