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

class PayOrderTimeoutJob implements ShouldQueue
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
        if (empty($order) || $order->pay_status->in([PayStatus::TIMEOUT, PayStatus::FINISH])) {
            return 0;
        }
        PayOrderService::update($order->id, [
            'pay_status' => PayStatus::TIMEOUT
        ]);
        return 1;
    }
}
