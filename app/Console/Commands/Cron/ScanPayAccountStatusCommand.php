<?php

namespace App\Console\Commands\Cron;

use App\Services\MerchantService;
use Exception;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class ScanPayAccountStatusCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'scan-pay-account-status';

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
    public function handle()
    {
        $baseUrl = 'https://api.polygonscan.com';
        $api = '/api';
        $params = [
            'module' => 'account',
            'action' => 'txlist',
            'address' => '0x09344d5B584401871cf2319E01191650B6541d0C',
            'startblock' => 0,
            'endblock' => 99999999,
            'page' => 1,
            'offset' => 10,
            'sort' => 'ASC',
            'apikey' => 'S9GZG3H3S5TW7SXX78761E2XEE3QACD8KQ'
        ];
        try {
            $response = Http::get($baseUrl . $api, $params);
            $data = json_decode($response->body(), true);
            if (!empty($data['status']) && $data['status'] == '1') {
                dd($data);
            }
//            if (!empty($data) && !empty($data[0]) && !empty($data[0]['current_price'])) {
//                Log::info('matic价格刷新成功');
//            } else {
//                Log::info('matic价格刷新失败');
//            }
        } catch (Exception $e) {
            Log::error($e->getMessage());
        }
        return self::SUCCESS;
    }
}
