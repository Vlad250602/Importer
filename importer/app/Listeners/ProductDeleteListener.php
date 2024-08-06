<?php

namespace App\Listeners;

use App\Events\ProductCreate;
use App\Events\ProductDelete;
use App\Traits\ProductEventMethods;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class ProductDeleteListener
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
    public function handle(ProductDelete $event): void
    {

        $this->updateCategoriesProductCount();

        $this->updateActiveProductsCountCache();
    }
}
