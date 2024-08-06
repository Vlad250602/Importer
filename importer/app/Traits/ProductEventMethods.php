<?php

namespace App\Traits;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Support\Facades\Cache;

trait ProductEventMethods
{
    public function checkEmptyProductPrice(Product $product){
        $price = $product->getAttribute('price');

        if (empty($price)){
            $temp = Product::findOrFail($product->id);
            $temp->updateQuietly(['status' => 'H']);
        }
    }

    public function updateActiveProductsCountCache(){
        Cache::set('active_products', Product::where('status', 'A')->count());
    }

    public function updateCategoriesProductCount(){
        $categories = Category::withCount('products')->get();

        foreach ($categories as $category){
            $category->update(['count_products' => $category->products_count]);
        }
    }

}
