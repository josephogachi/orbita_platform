<?php

namespace App\Livewire;

use App\Models\Product;
use App\Models\Category;
use Livewire\Component;
use Livewire\WithPagination;

class ProductList extends Component
{
    use WithPagination;

    public $search = '';
    public $selectedCategory = null;
    public $sortPrice = 'asc';

    // Reset pagination when searching
    public function updatingSearch() { $this->resetPage(); }

    public function render()
    {
        $products = Product::query()
            ->where('is_active', true)
            ->when($this->selectedCategory, function($query) {
                $query->where('category_id', $this->selectedCategory);
            })
            ->where('name', 'like', '%' . $this->search . '%')
            ->orderBy('price', $this->sortPrice)
            ->paginate(12);

        return view('livewire.product-list', [
            'products' => $products,
            'categories' => Category::all(),
        ]);
    }
}