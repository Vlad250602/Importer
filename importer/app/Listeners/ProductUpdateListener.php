<?php

namespace App\Listeners;

use App\Events\ProductCreate;
use App\Events\ProductUpdate;
use App\Traits\ProductEventMethods;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class ProductUpdateListener
{
    use ProductEventMethods;
    /**
     * Create the event listener.
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     */
    public function handle(ProductUpdate $event): void
    {
        $this->checkEmptyProductPrice($event->product);

        $this->updateCategoriesProductCount();

        $this->updateActiveProductsCountCache();
    }
}
