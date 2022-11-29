<?php

namespace App\Console\Commands\Cron;

use Exception;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class RefreshFantomPriceCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'refresh-fantom-price';

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
        $baseUrl = 'https://api.coingecko.com';
        $api = '/api/v3/coins/markets';
        $params = [
            'vs_currency' => 'usd',
            'ids' => 'fantom',
            'order' => 'market_cap_desc',
            'per_page' => 100,
            'page' => 1,
            'sparkline' => false
        ];
        try {
            $response = Http::get($baseUrl . $api, $params);
            $data = json_decode($response->body(), true);
            if (!empty($data) && !empty($data[0]) && !empty($data[0]['current_price'])) {
                Cache::put('fantom_current_price', $data[0]['current_price'], 3600);
                Log::info('fantom价格刷新成功');
            } else {

                Log::info('fantom价格刷新失败');
            }
        } catch (Exception $e) {
            Log::error($e->getMessage());
        }
        return self::SUCCESS;
    }
}
