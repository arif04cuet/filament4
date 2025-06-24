<?php

namespace App\Livewire;

use App\Models\Category;
use App\Models\GroceryItem;
use Livewire\Component;

class GroceryList extends Component
{
    public $groceryItems;
    public $categories;

    public function mount()
    {
        $this->groceryItems = GroceryItem::with(['category', 'unit'])->get();
        $this->categories = Category::orderBy('name')->get();
    }

    public function render()
    {
        return view('livewire.grocery-list');
    }
}
