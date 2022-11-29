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
        $baseUrl = 'http://127.0.0.1:8000';
        $api = '/api/web/pay-orders/store';
        $salt = Str::random(6);
        $key = 'EzaCu4FHLpBE8ZExwXayzmIEfISbylmP10V9OqN8CnxdNLUosbtlrbxawn4DFWtyy4uWrt4LoTeCz9ClQvkFY9qcn1num3AsNyO9SBPgBwNZECaLTWdFC5qNUSQhWJWf';
        $sign = ApplicationService::sign($key,$salt);
        $params = [
            'title' => '测试',
            'fee' => 2000,
            'remark'=>'xx',
            'app_id' => 'b6TI6NoWwEKIdYaHrICMMyTEJ2hB2mBIsLexCUmh4SegqmnfHKEG2JMLZ9wsABzqIrmgiFqfGbxe9ERKuF4PiCw378Q6yqPbwjqI5RnDdhDQI1gkf3ZC2rH9s3A9ZhGU',
            'salt' => $salt,
            'sign'=>$sign,
            'currency'=>1
        ];
        $response = Http::post($baseUrl . $api, $params);
        dd(json_decode($response->body(),true));
        return Command::SUCCESS;
    }
}
