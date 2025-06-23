<?php

namespace App\Filament\Resources\GroceryItems\Pages;

use App\Filament\Resources\GroceryItems\GroceryItemResource;
use Filament\Resources\Pages\CreateRecord;

class CreateGroceryItem extends CreateRecord
{
    protected static string $resource = GroceryItemResource::class;
}
