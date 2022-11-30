<?php

namespace App\Jobs;

use App\Enums\Fields\PayStatus;
use App\Services\Base\PayOrderService;
use Exception;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;

class PayOrderRefreshPayFeeJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected int $orderId;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(int $orderId)
    {
        $this->orderId = $orderId;
    }

    /**
     * Execute the job.
     *
     * @return int
     * @throws Exception
     */
    public function handle(): int
    {
        $order = PayOrderService::findById($this->orderId);
        if (empty($order) || PayStatus::NORMAL()->isNot($order->pay_status)) {
            return 0;
        }
        $nowPrice = floatval(Cache::get('fantom_current_price', '0'));
        if (empty($nowPrice)) {
            Log::error('价格更新失败!');
            return 0;
        }
        $nowPrice = intval(round($nowPrice, 2) * 100);
        $payFee = intval(round(intval($order->fee) / $nowPrice, 2) * 100);
        PayOrderService::update($order->id, [
            'pay_fee' => $payFee,
            'pay_refresh_at' => now()
        ]);
        self::dispatch($this->orderId)->delay(now()->addMinutes(2));
        return 1;
    }
}
