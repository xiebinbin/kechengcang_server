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
        dd(Storage::disk('doge')->put('testxx.txt', 'hello'));
        dd(CategoryService::treeData());
        $a = [1, 2, 4];
        $b = [1, 2, 3];
        $newCategoryIds = array_diff($a, $b);
        $delCategoryIds = array_diff($b, $a);
        sort($newCategoryIds);
        sort($delCategoryIds);
        dd($delCategoryIds, $newCategoryIds);
        $baseUrl = 'https://api.magapaypal.com';
        $api = '/api/web/pay-orders/store';
        $salt = Str::random(6);
        $key = 'So2LBFyyYHwUVjIWmY1uEauXL4wtIhCITWHaySNW8Yq5C2WciVtnmDL6lje0DubA';
        $sign = ApplicationService::sign($key, $salt);
        $params = [
            'title' => '测试',
            'fee' => 2000,
            'remark' => 'xx',
            'app_id' => '6eXc46XUBDlOUAQBjfbjHhRwkIEzQOL18FcyD9kym8WhDeQXkJeX1B8QTT6czyeO',
            'salt' => $salt,
            'sign' => $sign,
            'currency' => 1
        ];
        $response = Http::post($baseUrl . $api, $params);
        dd($response, json_decode($response->body(), true));
        return Command::SUCCESS;
    }
}
