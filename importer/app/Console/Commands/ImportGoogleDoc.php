<?php

namespace App\Console\Commands;

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
        $service->getDataFromSheets();
        $this->info($service->simple());
    }
}
