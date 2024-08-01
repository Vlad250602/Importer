<?php

namespace App\Jobs;

use App\Models\Category;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class ImportCategoriesJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    private array $categories_data;

    /**
     * Create a new job instance.
     */
    public function __construct($categories_data = [])
    {
        $this->categories_data = $categories_data;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {

        foreach ($this->categories_data as $category_data){
            $category = Category::firstOrNew(['code' => $category_data['code']]);
            $category->name = $category_data['name'];
            $category->save();
        }
    }
}
