<?php

namespace App\Observers;

use App\Models\Category;
use App\Models\Product;
use function Sodium\add;

class ProductObserver
{
    /**
     * Handle the Product "created" event.
     */
    public function created(Product $product): void
    {
        $this->changeCategoryProductCount($product->getAttribute('category_codes'),'add');
    }

    /**
     * Handle the Product "updated" event.
     */
    public function updated(Product $product): void
    {
        $this->changeCategoryProductCount(
            $product->getAttribute('category_codes'),
            'auto',
            $product->getOriginal('category_codes')
        );
    }

    /**
     * Handle the Product "deleted" event.
     */
    public function deleted(Product $product): void
    {
        //
    }

    /**
     * Handle the Product "restored" event.
     */
    public function restored(Product $product): void
    {
        //
    }

    /**
     * Handle the Product "force deleted" event.
     */
    public function forceDeleted(Product $product): void
    {
        //
    }

    private function changeCategoryProductCount($category_codes, $type = 'auto', $old_category_codes = []){


        if ($type == 'auto'){
            $categories = Category::whereIn('code', explode(',',preg_replace('/\s+/', '', $old_category_codes)))->get();

            foreach ($categories as $category){
                $category->count_products -= 1;
                $category->save();
            }


            $categories = Category::whereIn('code', explode(',',preg_replace('/\s+/', '', $category_codes)))->get();
            foreach ($categories as $category){
                $category->count_products += 1;
                $category->save();
            }
        } else {
            $quantity_change = 1;
            if ($type == 'remove'){
                $quantity_change = -1;
            }

            $categories = Category::whereIn('code', explode(',',preg_replace('/\s+/', '', $category_codes)))->get();

            foreach ($categories as $category){
                $category->count_products += $quantity_change;
                $category->save();
            }
        }
    }
}
