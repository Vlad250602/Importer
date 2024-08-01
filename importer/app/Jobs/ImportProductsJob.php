<?php

namespace App\Jobs;

use App\Models\Product;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class ImportProductsJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    private array $products_data;

    /**
     * Create a new job instance.
     */
    public function __construct($products_data = [])
    {
        $this->products_data = $products_data;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        foreach ($this->products_data as $product_data){
            $product = Product::firstOrNew(['code' => $product_data['code']]);
            $product->name = $product_data['name'];
            $product->price = floatval($product_data['price']);
            $product->status = $product_data['status'];
            $product->category_codes = $product_data['category-codes'];
            $product->save();
        }
    }
}
