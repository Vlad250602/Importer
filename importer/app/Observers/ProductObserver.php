<?php

namespace App\Observers;

use App\Events\ProductCreate;
use App\Events\ProductDelete;
use App\Events\ProductUpdate;
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
        event(new ProductCreate($product));
    }

    /**
     * Handle the Product "updated" event.
     */
    public function updated(Product $product): void
    {
        event(new ProductUpdate($product));
    }

    /**
     * Handle the Product "deleted" event.
     */
    public function deleted(Product $product): void
    {
        event(new ProductDelete($product));
    }


}
