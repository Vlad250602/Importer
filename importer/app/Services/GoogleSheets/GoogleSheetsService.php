<?php

namespace App\Services\GoogleSheets;

use Illuminate\Support\Facades\Http;

class GoogleSheetsService
{
    private string $products_sheet_url;
    private string $categories_sheet_url;


    public function __construct(){
        $this->products_sheet_url = 'https://docs.google.com/spreadsheets/d/1qAbpynrxbMvJSRwgNA7g4MEXUzSBr-rLMRi2APwGdpo/export?format=csv&gid=0#gid=0';

        $this->categories_sheet_url = 'https://docs.google.com/spreadsheets/d/1qAbpynrxbMvJSRwgNA7g4MEXUzSBr-rLMRi2APwGdpo/export?format=csv&gid=2111825381#gid=2111825381';
    }

    public function simple(){
        return 'service';
    }

    public function getDataFromSheets(){
        $file = fopen($this->products_sheet_url,"r");

        $columns = fgetcsv($file);

        print_r(fgetcsv($file));
        print_r(fgetcsv($file));
    }
}
