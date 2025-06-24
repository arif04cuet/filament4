@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-slate-100 to-sky-100 py-8 px-4" x-data="{
        searchTerm: '',
        selectedCategory: '',
        items: {{ Js::from($groceryItems->map(function($item) { $item->selected = false; $item->quantity_val = ''; return $item; })) }},
        categories: {{ Js::from($categories) }},
        resetList() {
            this.searchTerm = '';
            this.selectedCategory = '';
            this.items.forEach(item => {
                item.selected = false;
                item.quantity_val = '';
            });
        },
        showPreview: false,
        getFormattedShareText() {
            let text = 'Here my grocery list:\n'; 
            this.items.filter(i=> i.selected && i.quantity_val).forEach(item=> {
                text += `- ${item.name} (${item.quantity_val} ${item.unit.name})\n`;
            });
            return text;
        },
        get anyItemSelected() {
            return this.items.some(item => item.selected);
        },
        shareEmail: ''
    }">
    <div class="container mx-auto max-w-3xl">

        <header class="mb-10 text-center">
            <h1
                class="text-5xl font-extrabold mb-3 text-transparent bg-clip-text bg-gradient-to-r from-blue-500 to-teal-400">
                My Grocery List
            </h1>
            <p class="text-lg text-gray-600">Manage your shopping items with ease.</p>
        </header>

        <!-- Search and Filter -->
        <div class="mb-8 p-6 bg-white rounded-xl shadow-lg">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label for="searchItems" class="block text-sm font-medium text-gray-700 mb-1">Search Items</label>
                    <input type="text" id="searchItems" x-model.debounce.300ms="searchTerm"
                        placeholder="e.g., Apples, Milk..."
                        class="w-full p-3 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition duration-150 ease-in-out">
                </div>
                <div>
                    <label for="filterCategory" class="block text-sm font-medium text-gray-700 mb-1">Filter by
                        Category</label>
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

        <!-- Action Buttons -->
        <div class="mb-6 flex justify-between items-center">
            <button @click="resetList()"
                class="px-4 py-2 bg-red-500 text-white font-semibold rounded-lg shadow-md hover:bg-red-600 focus:outline-none focus:ring-2 focus:ring-red-400 focus:ring-opacity-75 transition ease-in-out duration-150">
                Reset List
            </button>
            <button x-show="anyItemSelected" @click="showPreview = true"
                class="px-4 py-2 bg-blue-500 text-white font-semibold rounded-lg shadow-md hover:bg-blue-600 focus:outline-none focus:ring-2 focus:ring-blue-400 focus:ring-opacity-75 transition ease-in-out duration-150"
                style="display: none;">
                <!-- Initially hidden if no items are selected, managed by Alpine x-show -->
                Preview List
            </button>
        </div>

        <!-- Grocery Items List -->
        <div class="space-y-3">
            <!-- Reduced space for compactness -->
            <template
                x-for="item in items.filter(i => (searchTerm === '' || i.name.toLowerCase().includes(searchTerm.toLowerCase())) && (selectedCategory === '' || i.category_id == selectedCategory))"
                :key="item.id">
                <div
                    class="bg-white p-4 rounded-lg shadow-md hover:shadow-lg transition-shadow duration-200 ease-in-out">
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
                        <div x-show="item.selected" class="flex items-center gap-2 ml-3 flex-shrink-0"
                            x-transition:enter="transition ease-out duration-200"
                            x-transition:enter-start="opacity-0 transform scale-90"
                            x-transition:enter-end="opacity-100 transform scale-100">
                            <input type="number" min="1" x-model.number="item.quantity_val"
                                @input="item.quantity_val = item.quantity_val ? parseInt(item.quantity_val) || 1 : null; if(item.quantity_val < 1 && item.quantity_val !== null) item.quantity_val = 1;"
                                :id="'quantity-' + item.id" placeholder="Qty"
                                class="w-20 p-1 border border-gray-300 rounded-md shadow-sm text-sm focus:outline-none focus:ring-1 focus:ring-indigo-500 focus:border-indigo-500 transition duration-150 ease-in-out [appearance:textfield] [&::-webkit-outer-spin-button]:appearance-none [&::-webkit-inner-spin-button]:appearance-none">
                            <label :for="'quantity-' + item.id" class="text-sm font-bold text-gray-700"
                                x-text="item.unit.name"></label>
                        </div>
                    </div>
                </div>
            </template>

            <!-- No items message -->
            <template
                x-if="items.filter(i => (searchTerm === '' || i.name.toLowerCase().includes(searchTerm.toLowerCase())) && (selectedCategory === '' || i.category_id == selectedCategory)).length === 0">
                <div class="text-center py-10 bg-white rounded-lg shadow-md">
                    <!-- Adjusted padding -->
                    <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"
                        aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z" />
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
            height: 1.5rem;
            /* h-6 */
            width: 1.5rem;
            /* w-6 */
            color: #4f46e5;
            /* indigo-600 */
            background-color: #fff;
            border-color: #6b7280;
            /* gray-500 */
            border-width: 1px;
            border-radius: 0.375rem;
            /* rounded */
        }

        .form-checkbox:focus {
            outline: 2px solid transparent;
            outline-offset: 2px;
            --tw-ring-inset: var(--tw-empty,
                    /*!*/
                    /*!*/
                );
            --tw-ring-offset-width: 2px;
            --tw-ring-offset-color: #fff;
            --tw-ring-color: #4f46e5;
            /* indigo-600 */
            --tw-ring-offset-shadow: var(--tw-ring-inset) 0 0 0 var(--tw-ring-offset-width) var(--tw-ring-offset-color);
            --tw-ring-shadow: var(--tw-ring-inset) 0 0 0 calc(2px + var(--tw-ring-offset-width)) var(--tw-ring-color);
            box-shadow: var(--tw-ring-offset-shadow), var(--tw-ring-shadow), var(--tw-shadow, 0 0 #0000);
            border-color: #4f46e5;
            /* indigo-600 */
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
            padding-right: 2.5rem;
            /* pr-10 for Tailwind */
        }
    </style>

    <!-- Preview Modal -->
    <div x-show="showPreview" class="fixed inset-0 bg-gray-800 bg-opacity-75 flex items-center justify-center p-4 z-50"
        x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0"
        x-transition:enter-end="opacity-100" x-transition:leave="transition ease-in duration-200"
        x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0" style="display: none;">
        <div @click.away="showPreview = false"
            class="bg-white rounded-lg shadow-xl w-full max-w-lg p-6 max-h-[80vh] overflow-y-auto">
            <div class="flex justify-between items-center mb-4">
                <h3 class="text-2xl font-semibold text-gray-800">Preview Selected Groceries</h3>
                <button @click="showPreview = false" class="text-gray-500 hover:text-gray-700 text-2xl">&times;</button>
            </div>

            <!-- Selected Items List -->
            <div class="space-y-3">
                <template x-for="item in items.filter(i => i.selected && i.quantity_val)" :key="item.id">
                    <div class="flex justify-between items-center p-3 bg-gray-50 rounded-md">
                        <span class="font-medium text-gray-700" x-text="item.name"></span>
                        <div class="font-semibold text-gray-800">
                            <span x-text="item.quantity_val"></span>
                            <span class="font-bold" x-text="item.unit.name"></span>
                        </div>
                    </div>
                </template>
                <template x-if="items.filter(i => i.selected && i.quantity_val).length === 0">
                    <p class="text-gray-600 text-center py-4">No items selected or no quantities entered for selected
                        items.</p>
                </template>
            </div>

            <!-- Sharing options will be added here in the next step -->
            <div class="mt-6 pt-4 border-t">
                <h4 class="text-lg font-medium text-gray-700 mb-3">Share this list:</h4>
                <div class="mb-4">
                    <label for="shareEmailInput" class="block text-sm font-medium text-gray-700 mb-1">Recipient's Email
                        (optional):</label>
                    <input type="email" id="shareEmailInput" x-model="shareEmail" placeholder="Enter email address"
                        class="w-full p-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-1 focus:ring-indigo-500 focus:border-indigo-500 transition duration-150 ease-in-out">
                </div>
                <div class="flex flex-col sm:flex-row justify-center items-center gap-3 sm:space-x-3">
                    <a :href="'mailto:' + shareEmail + '?subject=My Grocery List&body=' + encodeURIComponent(getFormattedShareText())"
                        class="w-full sm:w-auto flex items-center justify-center px-4 py-2 bg-gray-600 text-white font-semibold rounded-lg shadow-md hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-opacity-75 transition ease-in-out duration-150">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" viewBox="0 0 20 20"
                            fill="currentColor">
                            <path d="M2.003 5.884L10 9.882l7.997-3.998A2 2 0 0016 4H4a2 2 0 00-1.997 1.884z" />
                            <path d="M18 8.118l-8 4-8-4V14a2 2 0 002 2h12a2 2 0 002-2V8.118z" />
                        </svg>
                        Email
                    </a>
                    <a :href="'https://api.whatsapp.com/send?text=' + encodeURIComponent(getFormattedShareText())"
                        target="_blank"
                        class="w-full sm:w-auto flex items-center justify-center px-4 py-2 bg-green-500 text-white font-semibold rounded-lg shadow-md hover:bg-green-600 focus:outline-none focus:ring-2 focus:ring-green-400 focus:ring-opacity-75 transition ease-in-out duration-150">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="currentColor"
                            viewBox="0 0 24 24">
                            <path
                                d="M.057 24l1.687-6.163c-1.041-1.804-1.588-3.849-1.587-5.946.003-6.556 5.338-11.891 11.893-11.891 3.181.001 6.167 1.24 8.413 3.488 2.245 2.248 3.481 5.236 3.48 8.414-.003 6.557-5.338 11.892-11.893 11.892-1.99-.001-3.951-.5-5.688-1.448L.057 24zm6.597-3.807c1.676.995 3.276 1.591 5.392 1.592 5.448 0 9.886-4.434 9.889-9.885.002-5.462-4.415-9.89-9.881-9.892-5.452 0-9.887 4.434-9.889 9.884-.001 2.225.651 3.891 1.746 5.634l-.999 3.648 3.742-.981zm11.387-5.464c-.074-.124-.272-.198-.57-.347-.297-.149-1.758-.868-2.031-.967-.272-.099-.47-.149-.669.149-.198.297-.768.967-.941 1.165-.173.198-.347.223-.644.074-.297-.149-1.255-.462-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.297-.347.446-.521.149-.173.198-.297.297-.495.099-.198.05-.372-.025-.521-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01s-.521.074-.792.372c-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.626.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.695.248-1.289.173-1.413z" />
                        </svg>
                        WhatsApp
                    </a>
                </div>
            </div>

            <div class="mt-6 text-right">
                <button @click="showPreview = false"
                    class="px-4 py-2 bg-gray-300 text-gray-800 font-semibold rounded-lg hover:bg-gray-400 focus:outline-none focus:ring-2 focus:ring-gray-400 focus:ring-opacity-75 transition ease-in-out duration-150">
                    Close
                </button>
            </div>
        </div>
    </div>
</div>
@endsection