<?php

namespace App\Filament\Resources\OrderItemVariantResource\Pages;

use App\Filament\Resources\OrderItemVariantResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditOrderItemVariant extends EditRecord
{
    protected static string $resource = OrderItemVariantResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
