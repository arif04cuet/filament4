<?php

use Illuminate\Support\Facades\Route;

use App\Livewire\GroceryList;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/groceries', GroceryList::class)->name('groceries');
