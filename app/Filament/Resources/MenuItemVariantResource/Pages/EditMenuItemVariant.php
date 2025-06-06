<?php

namespace App\Filament\Resources\MenuItemVariantResource\Pages;

use App\Filament\Resources\MenuItemVariantResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditMenuItemVariant extends EditRecord
{
    protected static string $resource = MenuItemVariantResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
