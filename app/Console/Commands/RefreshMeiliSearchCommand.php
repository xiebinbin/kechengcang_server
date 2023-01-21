<?php

namespace App\Console\Commands;

use App\Enums\Fields\Role;
use App\Models\Course;
use App\Services\Admin\AdminUserService;
use Exception;
use Illuminate\Console\Command;
use MeiliSearch\Client;
use Meilisearch\Contracts\IndexesQuery;

class RefreshMeiliSearchCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'refresh-meilisearch';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     *
     * @return int
     * @throws Exception
     */
    public function handle(): int
    {
        $host = config('scout.meilisearch.host');
        $key = config('scout.meilisearch.key');
        $client = new Client($host, $key);
        $index = (new Course())->searchableAs();

        $client->createIndex($index);
        $client->index($index)->updateSortableAttributes([
            'id',
            'sort',
            'recommend_status',
        ]);
        $client->index($index)->updateSearchableAttributes(['title', 'intro']);
        $client->index($index)->updateFilterableAttributes(['category_ids', 'online_status', 'recommend_status', 'subject_ids', 'channel_ids']);
        return self::SUCCESS;
    }
}
