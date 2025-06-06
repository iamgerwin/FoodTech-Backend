<?php

namespace App\Filament\Resources\RestaurantBranchResource\Pages;

use App\Filament\Resources\RestaurantBranchResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListRestaurantBranches extends ListRecords
{
    protected static string $resource = RestaurantBranchResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
