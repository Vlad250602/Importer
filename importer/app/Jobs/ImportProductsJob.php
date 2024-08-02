<?php

namespace App\Jobs;

use App\Models\Category;
use App\Models\Product;
use App\Models\ProductCategory;
use App\Models\QueueStatus;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;

class ImportProductsJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    private array $products_data;
    private QueueStatus $queue_stats;

    /**
     * Create a new job instance.
     */
    public function __construct($products_data, QueueStatus $queue_stats)
    {
        $this->products_data = $products_data;
        $this->queue_stats = $queue_stats;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        foreach ($this->products_data as $product_data){
            try{
                /** @var Product $product */
                $product = Product::updateOrCreate(
                    [
                        'code' => $product_data['code']
                    ],
                    [
                        'name'           => $product_data['name'],
                        'price'          => strlen($product_data['price']) == 0  ? null : floatval($product_data['price']),
                        'status'         => $product_data['status'],
                    ]);

                $product_category_codes = explode(',',preg_replace('/\s+/', '', $product_data['category-codes']));

                if (!empty($product_category_codes) && $product){
                    $category_ids = Category::whereIn('code', $product_category_codes)->pluck('id')->toArray();

                    if (!empty($category_ids)){
                        $product->categories()->sync($category_ids);
                    }
                }

            } finally {
                $this->queue_stats->processed++;
                $this->queue_stats->save();
            };
        }

        $active_product_count = count(Product::where('status', 'A')->get());
        Cache::set('active_products', $active_product_count);

        if ($this->queue_stats->total <= $this->queue_stats->processed){
            $this->queue_stats->total = 0;
            $this->queue_stats->processed = 0;
            $this->queue_stats->save();
        }
    }
}
