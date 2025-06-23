<?php

namespace App\Filament\Resources\GroceryItems\Pages;

use App\Filament\Resources\GroceryItems\GroceryItemResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditGroceryItem extends EditRecord
{
    protected static string $resource = GroceryItemResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
