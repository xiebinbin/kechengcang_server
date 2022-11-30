<?php

namespace App\Console\Commands;

use App\Services\Base\ApplicationService;
use App\Services\MerchantService;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;
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
     */
    public function handle()
    {
        $baseUrl = 'https://api.magapaypal.com';
        $api = '/api/web/pay-orders/store';
        $salt = Str::random(6);
        $key = '6eXc46XUBDlOUAQBjfbjHhRwkIEzQOL18FcyD9kym8WhDeQXkJeX1B8QTT6czyeO';
        $sign = ApplicationService::sign($key,$salt);
        $params = [
            'title' => '测试',
            'fee' => 2000,
            'remark'=>'xx',
            'app_id' => 'So2LBFyyYHwUVjIWmY1uEauXL4wtIhCITWHaySNW8Yq5C2WciVtnmDL6lje0DubA',
            'salt' => $salt,
            'sign'=>$sign,
            'currency'=>1
        ];
        $response = Http::post($baseUrl . $api, $params);
        dd(json_decode($response->body(),true));
        return Command::SUCCESS;
    }
}
