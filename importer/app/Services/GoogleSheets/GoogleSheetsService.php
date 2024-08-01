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

    public function getDataFromSheets($type = ''){
        if (!empty($type)){
            return $this->parseCSV($type);
        }

        $data['products'] = $this->parseCSV('products');
        $data['categories'] = $this->parseCSV('categories');

        return $data;

    }

    private function parseCSV($type){

        $result = [];

        if ($type == 'categories'){
            $file = fopen($this->categories_sheet_url,"r");

        } elseif($type == 'products'){
            $file = fopen($this->products_sheet_url,"r");

        } else {
            return $result;
        }

        $columns = fgetcsv($file);

        $counter = 0;

        while (($row = fgetcsv($file)) !== FALSE) {
            foreach ($columns as $key => $column){
                $result[$counter][$column] = $row[$key];
            }
            $counter++;
        }
        return $result;
    }
}





















