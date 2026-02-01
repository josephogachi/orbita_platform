<?php

namespace App\Livewire;

use App\Models\Product;
use App\Models\Category;
use Livewire\Component;
use Livewire\WithPagination;

class ProductFilters extends Component
{
    use WithPagination;

    // Filter States
    public $selectedCategories = [];
    public $maxPrice = 200000;
    public $inStockOnly = false;
    public $sort = 'latest';

    // Reset pagination when filters change
    public function updated($propertyName)
    {
        $this->resetPage();
    }

    public function render()
    {
        $query = Product::query()->where('is_active', true);

        // Filter by Category
        if (!empty($this->selectedCategories)) {
            $query->whereIn('category_id', $this->selectedCategories);
        }

        // Filter by Price
        $query->where('price', '<=', $this->maxPrice);

        // Filter by Stock
        if ($this->inStockOnly) {
            $query->where('stock_quantity', '>', 0);
        }

        // Sorting Logic
        switch ($this->sort) {
            case 'price_low': $query->orderBy('price', 'asc'); break;
            case 'price_high': $query->orderBy('price', 'desc'); break;
            default: $query->orderBy('created_at', 'desc'); break;
        }

        return view('livewire.product-filters', [
            'products' => $query->paginate(9),
            'categories' => Category::withCount('products')->get(),
        ]);
    }
}
