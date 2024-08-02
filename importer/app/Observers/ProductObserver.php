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
        Cache::set('active_products', Product::where('status', 'A')->count());

    }

    /**
     * Handle the Product "updated" event.
     */
    public function updated(Product $product): void
    {

        $this->checkEmptyProductPrice($product);

        Cache::set('active_products', Product::where('status', 'A')->count());
    }

    /**
     * Handle the Product "deleted" event.
     */
    public function deleted(Product $product): void
    {
        Cache::set('active_products', Product::where('status', 'A')->count());

    }

    /**
     * Handle the Product "restored" event.
     */
    public function restored(Product $product): void
    {

    }

    /**
     * Handle the Product "force deleted" event.
     */
    public function forceDeleted(Product $product): void
    {

    }

    private function checkEmptyProductPrice(Product $product){
        $price = $product->getAttribute('price');

        if (empty($price)){
            $temp = Product::findOrFail($product->id);
            $temp->updateQuietly(['status' => 'H']);
        }
    }

    
}
