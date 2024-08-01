<?php

namespace App\Jobs;

use App\Models\Category;
use App\Models\QueueStatus;
use Exception;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class ImportCategoriesJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    private array $categories_data;
    private QueueStatus $queue_stats;

    /**
     * Create a new job instance.
     */
    public function __construct($categories_data, QueueStatus $queue_stats)
    {
        $this->categories_data = $categories_data;
        $this->queue_stats = $queue_stats;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {

        foreach ($this->categories_data as $category_data) {
            try {
                Category::updateOrCreate(
                    [
                        'code' => $category_data['code']
                    ],
                    [
                        'name' => $category_data['name'],
                    ]);
            } finally {
                $this->queue_stats->processed++;
                $this->queue_stats->save();
            };

        }
        if ($this->queue_stats->total <= $this->queue_stats->processed) {
            $this->queue_stats->total = 0;
            $this->queue_stats->processed = 0;
            $this->queue_stats->save();
        }
    }
}
