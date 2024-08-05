<?php

namespace App\Observers;

use App\Models\Category;
use App\Models\Product;
use App\Models\ProductCategory;
use Illuminate\Support\Facades\Cache;

class ProductObserver
{
    /**
     * Handle the Product "created" event.
     */
    public function created(Product $product): void
    {
        $this->checkEmptyProductPrice($product);

        $this->updateCategoriesProductCount();

        $this->updateActiveProductsCountCache();

    }

    /**
     * Handle the Product "updated" event.
     */
    public function updated(Product $product): void
    {
        $this->checkEmptyProductPrice($product);

        $this->updateCategoriesProductCount();

        $this->updateActiveProductsCountCache();
    }

    /**
     * Handle the Product "deleted" event.
     */
    public function deleted(Product $product): void
    {
        $this->updateActiveProductsCountCache();
        $this->updateCategoriesProductCount();
    }


    private function checkEmptyProductPrice(Product $product){
        $price = $product->getAttribute('price');

        if (empty($price)){
            $temp = Product::findOrFail($product->id);
            $temp->updateQuietly(['status' => 'H']);
        }
    }

    private function updateActiveProductsCountCache(){
        Cache::set('active_products', Product::where('status', 'A')->count());
    }

    private function updateCategoriesProductCount(){
        $categories = Category::withCount('products')->get();

        foreach ($categories as $category){
            $category->update(['count_products' => $category->products_count]);
        }
    }
}
