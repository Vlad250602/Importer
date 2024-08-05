<?php

namespace App\Console\Commands;

use App\Jobs\ImportCategoriesJob;
use App\Jobs\ImportProductsJob;
use App\Models\Product;
use App\Models\QueueStatus;
use App\Services\GoogleSheets\GoogleSheetsService;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Cache;


class ImportGoogleDoc extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:import-google-doc';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    public function handle(GoogleSheetsService $service)
    {
        $category_queue_stat = QueueStatus::firstOrCreate(['queue_name' => 'categories']);

        if ($category_queue_stat->total >= $category_queue_stat->processed) {

            $categories_data = $service->getDataFromSheets('categories');
            $category_queue_stat->total = count($categories_data);
            $category_queue_stat->save();
            foreach (array_chunk($categories_data, 5) as $categories_chunk) {
                ImportCategoriesJob::dispatchSync($categories_chunk, $category_queue_stat);
            }
        }

        $product_queue_stat = QueueStatus::firstOrCreate(['queue_name' => 'products']);

        if ($product_queue_stat->total >= $product_queue_stat->processed) {

            $products_data = $service->getDataFromSheets('products');
            $product_queue_stat->total = count($products_data);
            $product_queue_stat->save();
            foreach (array_chunk($products_data, 5) as $product_chunk) {
                ImportProductsJob::dispatch($product_chunk, $product_queue_stat)->onQueue('products');
            }
        }
        echo PHP_EOL . Cache::get('active_products') . PHP_EOL;
        $this->info('Jobs has been added to queues');
    }
}
