<?php

namespace App\Console\Commands;

use App\Jobs\ImportCategoriesJob;
use App\Jobs\ImportProductsJob;
use App\Services\GoogleSheets\GoogleSheetsService;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\App;


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
        $data = $service->getDataFromSheets();

        foreach (array_chunk($data['products'],5) as $product_chunk){
            ImportProductsJob::dispatch($product_chunk)->onQueue('products');
        }

        foreach (array_chunk($data['categories'],5) as $categories_chunk){
            ImportCategoriesJob::dispatch($categories_chunk)->onQueue('categories');
        }

        $this->info('Jobs has been added to queues');
    }
}
