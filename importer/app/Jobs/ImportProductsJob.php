<?php

namespace App\Jobs;

use App\Models\Product;
use App\Models\QueueStatus;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

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
            $product = Product::firstOrNew(['code' => $product_data['code']]);
            $product->name = $product_data['name'];
            $product->price = floatval($product_data['price']);
            $product->status = $product_data['status'];
            $product->category_codes = $product_data['category-codes'];
            $product->save();
            } finally {
                $this->queue_stats->processed++;
                $this->queue_stats->save();
            };
        }
        if ($this->queue_stats->total <= $this->queue_stats->processed){
            $this->queue_stats->total = 0;
            $this->queue_stats->processed = 0;
            $this->queue_stats->save();
        }
    }
}
