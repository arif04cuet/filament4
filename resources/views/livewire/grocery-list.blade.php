@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-slate-100 to-sky-100 py-8 px-4" x-data="{ searchTerm: '', selectedCategory: '', items: {{ Js::from($groceryItems) }}, categories: {{ Js::from($categories) }} }">
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
        <div class="space-y-5">
            <template x-for="item in items.filter(i => (searchTerm === '' || i.name.toLowerCase().includes(searchTerm.toLowerCase())) && (selectedCategory === '' || i.category_id == selectedCategory))" :key="item.id">
                <div x-data="{ checked: false }" class="bg-white p-6 rounded-xl shadow-lg hover:shadow-2xl transition-all duration-300 ease-in-out transform hover:-translate-y-1">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center gap-4">
                             <input type="checkbox" x-model="checked" :id="'item-checkbox-' + item.id"
                                   class="form-checkbox h-6 w-6 text-indigo-600 border-gray-300 rounded focus:ring-indigo-500 focus:ring-offset-2 transition duration-150 ease-in-out cursor-pointer">
                            <div>
                                <label :for="'item-checkbox-' + item.id" class="cursor-pointer">
                                    <h2 class="text-xl font-semibold text-gray-800" x-text="item.name"></h2>
                                </label>
                                <p class="text-sm text-gray-500">
                                    <span class="font-medium" x-text="item.category.name"></span> &bull; <span class="italic" x-text="item.unit.name"></span>
                                </p>
                            </div>
                        </div>
                    </div>
                    <div x-show="checked" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 transform -translate-y-2" x-transition:enter-end="opacity-100 transform translate-y-0"
                         x-transition:leave="transition ease-in duration-150" x-transition:leave-start="opacity-100 transform translate-y-0" x-transition:leave-end="opacity-0 transform -translate-y-2"
                         class="mt-4 pt-4 border-t border-gray-200">
                        <label :for="'quantity-' + item.id" class="block text-sm font-medium text-gray-700 mb-1">Quantity for <span x-text="item.name" class="font-semibold"></span>:</label>
                        <input type="text" :id="'quantity-' + item.id" placeholder="e.g., 2, 1 pack"
                               class="w-full p-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-1 focus:ring-indigo-500 focus:border-indigo-500 transition duration-150 ease-in-out">
                    </div>
                </div>
            </template>

            <!-- No items message -->
            <template x-if="items.filter(i => (searchTerm === '' || i.name.toLowerCase().includes(searchTerm.toLowerCase())) && (selectedCategory === '' || i.category_id == selectedCategory)).length === 0">
                <div class="text-center py-12 bg-white rounded-xl shadow-md">
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
