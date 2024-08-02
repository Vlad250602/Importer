<?php

namespace App\Observers;

use App\Models\Category;
use App\Models\Product;
use App\Models\ProductCategory;

class ProductObserver
{
    /**
     * Handle the Product "created" event.
     */
    public function created(Product $product): void
    {
        $this->checkEmptyProductPrice($product);
    }

    /**
     * Handle the Product "updated" event.
     */
    public function updated(Product $product): void
    {
        $this->checkEmptyProductPrice($product);
    }

    /**
     * Handle the Product "deleted" event.
     */
    public function deleted(Product $product): void
    {

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
            $product->updateQuietly(['status' => 'H']);
        }
    }

}
