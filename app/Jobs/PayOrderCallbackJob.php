<?php

namespace App\Jobs;

use App\Enums\Fields\CallbackStatus;
use App\Services\Base\ApplicationService;
use App\Services\Base\PayOrderService;
use Exception;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Http;
use Vinkla\Hashids\Facades\Hashids;

class PayOrderCallbackJob implements ShouldQueue
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
        if (empty($order)) {
            return 0;
        }
        $callbackTimes = $order->callback_times + 1;
        $updateData = [
            'callback_times' => $callbackTimes,
        ];
        if (!empty($order->callback_url) && $order->callback_status == CallbackStatus::NORMAL) {

            $application = ApplicationService::findById($order->application_id);
            $orderData = $order->toArray();
            $hashids = new Hashids();
            $orderData['id'] = $hashids->encode($orderData['id']);
            $orderData['app_id'] = $application->app_id;
            unset($order['admin_user_id']);
            unset($order['application_id']);
            $response = Http::post($order->callback_url, $orderData);


            if ($response->body() == 'ok') {
                $updateData['callback_status'] = CallbackStatus::FINISH;
            } else {
                if ($callbackTimes > 6) {
                    $updateData['callback_status'] = CallbackStatus::TIMEOUT;
                } else {
                    self::dispatch($this->orderId)->delay(now()->addMinutes($callbackTimes * 30));
                }
            }
        } else {
            $updateData['callback_status'] = CallbackStatus::FINISH;
        }
        PayOrderService::update($order->id, $updateData);
        return 1;
    }
}
