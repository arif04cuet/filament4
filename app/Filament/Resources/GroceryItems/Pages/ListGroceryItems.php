<?php

namespace App\Filament\Resources\GroceryItems\Pages;

use App\Filament\Resources\GroceryItems\GroceryItemResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListGroceryItems extends ListRecords
{
    protected static string $resource = GroceryItemResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make()->slideOver(),
        ];
    }
}
