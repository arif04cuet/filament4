<?php

namespace App\Filament\Resources\GroceryItems;

use App\Filament\Resources\GroceryItems\Pages\CreateGroceryItem;
use App\Filament\Resources\GroceryItems\Pages\EditGroceryItem;
use App\Filament\Resources\GroceryItems\Pages\ListGroceryItems;
use App\Filament\Resources\GroceryItems\Schemas\GroceryItemForm;
use App\Filament\Resources\GroceryItems\Tables\GroceryItemsTable;
use App\Models\GroceryItem;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class GroceryItemResource extends Resource
{
    protected static ?string $model = GroceryItem::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    public static function form(Schema $schema): Schema
    {
        return GroceryItemForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return GroceryItemsTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListGroceryItems::route('/'),
        ];
    }
}
