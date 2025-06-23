@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-slate-100 to-sky-100 py-8 px-4"
     x-data="{
        searchTerm: '',
        selectedCategory: '',
        items: {{ Js::from($groceryItems->map(function($item) { $item->selected = false; $item->quantity_val = ''; return $item; })) }},
        categories: {{ Js::from($categories) }}
     }">
    <div class="container mx-auto max-w-3xl">

        <header class="mb-10 text-center">
            <h1 class="text-5xl font-extrabold mb-3 text-transparent bg-clip-text bg-gradient-to-r from-blue-500 to-teal-400">
                My Grocery List
            </h1>
            <p class="text-lg text-gray-600">Manage your shopping items with ease.</p>
        </header>

        <!-- Search and Filter -->
        <div class="mb-8 p-6 bg-white rounded-xl shadow-lg">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label for="searchItems" class="block text-sm font-medium text-gray-700 mb-1">Search Items</label>
                    <input type="text" id="searchItems" x-model.debounce.300ms="searchTerm" placeholder="e.g., Apples, Milk..."
                           class="w-full p-3 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition duration-150 ease-in-out">
                </div>
                <div>
                    <label for="filterCategory" class="block text-sm font-medium text-gray-700 mb-1">Filter by Category</label>
                    <select id="filterCategory" x-model="selectedCategory"
                            class="w-full p-3 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition duration-150 ease-in-out appearance-none bg-white pr-8">
                        <option value="">All Categories</option>
                        <template x-for="category in categories" :key="category.id">
                            <option :value="category.id" x-text="category.name"></option>
                        </template>
                    </select>
                </div>
            </div>
        </div>

        <!-- Grocery Items List -->
        <div class="space-y-3"> <!-- Reduced space for compactness -->
            <template x-for="item in items.filter(i => (searchTerm === '' || i.name.toLowerCase().includes(searchTerm.toLowerCase())) && (selectedCategory === '' || i.category_id == selectedCategory))" :key="item.id">
                <div class="bg-white p-4 rounded-lg shadow-md hover:shadow-lg transition-shadow duration-200 ease-in-out">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center flex-grow gap-3">
                            <input type="checkbox" x-model="item.selected" :id="'item-checkbox-' + item.id"
                                   class="form-checkbox h-5 w-5 text-indigo-600 border-gray-300 rounded focus:ring-indigo-500 focus:ring-offset-1 transition duration-150 ease-in-out cursor-pointer flex-shrink-0">
                            <label :for="'item-checkbox-' + item.id" class="cursor-pointer flex-grow">
                                <span class="text-lg font-medium text-gray-800" x-text="item.name"></span>
                                <p class="text-xs text-gray-500">
                                    <span x-text="item.category.name"></span>
                                </p>
                            </label>
                        </div>
                        <div x-show="item.selected" class="flex items-center gap-2 ml-3 flex-shrink-0" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 transform scale-90" x-transition:enter-end="opacity-100 transform scale-100">
                            <input type="text" x-model="item.quantity_val" :id="'quantity-' + item.id" placeholder="Qty"
                                   class="w-16 p-1 border border-gray-300 rounded-md shadow-sm text-sm focus:outline-none focus:ring-1 focus:ring-indigo-500 focus:border-indigo-500 transition duration-150 ease-in-out">
                            <label :for="'quantity-' + item.id" class="text-sm font-bold text-gray-700" x-text="item.unit.name"></label>
                        </div>
                    </div>
                </div>
            </template>

            <!-- No items message -->
            <template x-if="items.filter(i => (searchTerm === '' || i.name.toLowerCase().includes(searchTerm.toLowerCase())) && (selectedCategory === '' || i.category_id == selectedCategory)).length === 0">
                <div class="text-center py-10 bg-white rounded-lg shadow-md"> <!-- Adjusted padding -->
                    <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z" />
                    </svg>
                    <h3 class="mt-2 text-lg font-medium text-gray-900">No Grocery Items</h3>
                    <p class="mt-1 text-sm text-gray-500">
                        No items match your current search or filter. Try adjusting your criteria.
                    </p>
                </div>
            </template>
        </div>
    </div>

    <!-- Custom checkbox style to ensure Tailwind's default form plugin styles are applied if not already available globally -->
    <style>
        /* Ensure the Filament's form styles or a global Tailwind form setup provides these.
           This is a fallback/example if they are missing for the frontend. */
        .form-checkbox {
            -webkit-appearance: none;
            -moz-appearance: none;
            appearance: none;
            padding: 0;
            -webkit-print-color-adjust: exact;
            color-adjust: exact;
            display: inline-block;
            vertical-align: middle;
            background-origin: border-box;
            -webkit-user-select: none;
            -moz-user-select: none;
            user-select: none;
            flex-shrink: 0;
            height: 1.5rem; /* h-6 */
            width: 1.5rem; /* w-6 */
            color: #4f46e5; /* indigo-600 */
            background-color: #fff;
            border-color: #6b7280; /* gray-500 */
            border-width: 1px;
            border-radius: 0.375rem; /* rounded */
        }
        .form-checkbox:focus {
            outline: 2px solid transparent;
            outline-offset: 2px;
            --tw-ring-inset: var(--tw-empty,/*!*/ /*!*/);
            --tw-ring-offset-width: 2px;
            --tw-ring-offset-color: #fff;
            --tw-ring-color: #4f46e5; /* indigo-600 */
            --tw-ring-offset-shadow: var(--tw-ring-inset) 0 0 0 var(--tw-ring-offset-width) var(--tw-ring-offset-color);
            --tw-ring-shadow: var(--tw-ring-inset) 0 0 0 calc(2px + var(--tw-ring-offset-width)) var(--tw-ring-color);
            box-shadow: var(--tw-ring-offset-shadow), var(--tw-ring-shadow), var(--tw-shadow, 0 0 #0000);
             border-color: #4f46e5; /* indigo-600 */
        }
        .form-checkbox:checked {
            border-color: transparent;
            background-color: currentColor;
            background-size: 100% 100%;
            background-position: center;
            background-repeat: no-repeat;
        }
        .form-checkbox:checked {
            background-image: url("data:image/svg+xml,%3csvg viewBox='0 0 16 16' fill='white' xmlns='http://www.w3.org/2000/svg'%3e%3cpath d='M12.207 4.793a1 1 0 010 1.414l-5 5a1 1 0 01-1.414 0l-2-2a1 1 0 011.414-1.414L6.5 9.086l4.293-4.293a1 1 0 011.414 0z'/%3e%3c/svg%3e");
        }
        /* Ensure select has a dropdown arrow if not provided by a forms plugin */
        select.appearance-none {
            background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' fill='none' viewBox='0 0 20 20'%3E%3Cpath stroke='%236b7280' stroke-linecap='round' stroke-linejoin='round' stroke-width='1.5' d='M6 8l4 4 4-4'/%3E%3C/svg%3E");
            background-position: right 0.5rem center;
            background-repeat: no-repeat;
            background-size: 1.5em 1.5em;
            padding-right: 2.5rem; /* pr-10 for Tailwind */
        }
    </style>
</div>
@endsection
