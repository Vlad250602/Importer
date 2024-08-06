<?php

namespace App\Listeners;

use App\Events\ProductCreate;
use App\Traits\ProductEventMethods;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class ProductCreateListener
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
    public function handle(ProductCreate $event): void
    {
        $this->checkEmptyProductPrice($event->product);

        $this->updateCategoriesProductCount();

        $this->updateActiveProductsCountCache();
    }
}
