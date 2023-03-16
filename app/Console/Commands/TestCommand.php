<?php

namespace App\Console\Commands;

use App\Models\Subject;
use App\Services\Admin\UploadService;
use App\Services\Base\ApplicationService;
use App\Services\Base\CategoryService;
use App\Services\MerchantService;
use GuzzleHttp\Client;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Orhanerday\OpenAi\OpenAi;
use Ufree\LaravelDogeCloud\DogeCloud;
use Vinkla\Hashids\Facades\Hashids;

class TestCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'test:cmd';

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
     * @throws \Exception
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function handle()
    {


        return self::SUCCESS;
    }
}
