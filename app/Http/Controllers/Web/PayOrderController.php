<?php

namespace App\Http\Controllers\Web;

use App\Enums\Fields\DigitalCurrency;
use App\Enums\Fields\PayStatus;
use App\Http\Controllers\Controller;
use App\Http\Requests\Web\PayOrder\SubmittedRequest;
use App\Http\Requests\Web\PayOrder\ShowRequest;
use App\Http\Requests\Web\PayOrder\StoreRequest;
use App\Jobs\PayOrderRefreshPayFeeJob;
use App\Jobs\PayOrderTimeoutJob;
use App\Services\Base\ApplicationService;
use App\Services\Base\PayOrderService;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Cache;
use Vinkla\Hashids\Facades\Hashids;

class PayOrderController extends Controller
{
    /**
     * @throws Exception
     */
    public function store(StoreRequest $request): JsonResponse
    {
        $appId = $request->input('app_id');
        $application = ApplicationService::findByAppId($appId);
        if (empty($application)) {
            throw new Exception('应用不存在!');
        }
        // 验证签名
        $sign = $request->input('sign');
        $salt = $request->input('salt');
        if (!ApplicationService::checkSign($application->secure_key, $sign, $salt)) {
            throw new Exception('签名不正确!');
        }
        $nowPrice = floatval(Cache::get('fantom_current_price', '0'));
        if (empty($nowPrice)) {
            throw new Exception('价格系统出错!');
        }

        $nowPrice = intval(round($nowPrice, 2) * 100);
        $data = $request->all(['title', 'remark', 'fee', 'currency', 'callback_url', 'redirect_url']);
        $data['pay_fee'] = intval(round(intval($data['fee']) / $nowPrice, 2) * 100);
        $data['pay_currency'] = DigitalCurrency::ERC20_FTM;
        $data['admin_user_id'] = $application->admin_user_id;
        $data['application_id'] = $application->id;
        $data['receipt_account'] = '0xE4758EF12D49893581f71e6abdfB1ddA16a043ab';
        $order = PayOrderService::create($data)->toArray();
        PayOrderTimeoutJob::dispatch($order['id'])->delay(now()->addMinutes(30));
        PayOrderRefreshPayFeeJob::dispatch($order['id'])->delay(now()->addMinutes(2));
        $order['id'] = Hashids::encode($order['id']);
        $order['app_id'] = $application->app_id;
        unset($order['admin_user_id']);
        unset($order['application_id']);
        $order['tv_url'] = 'https://web3-pay.leleshuju/web/#/?order_id=' . $order['id'];
        return $this->success($order);
    }

    /**
     * @param ShowRequest $request
     * @return JsonResponse
     */
    public function show(ShowRequest $request): JsonResponse
    {
        $hashId = $request->input('id');
        $ids = Hashids::decode($hashId);
        if (!empty($ids)) {
            $id = $ids[0];
            $order = PayOrderService::findById($id);
            $order->app_id = $order->application->app_id;
            $order = $order->toArray();
            $order['id'] = $hashId;
            unset($order['admin_user_id']);
            unset($order['application_id']);
            $order['tv_url'] = 'https://web3-pay.leleshuju/web/#/?order_id=' . $order['id'];
            return $this->success($order);
        }
        return $this->success();
    }

    /**
     * @param SubmittedRequest $request
     * @return JsonResponse
     * @throws Exception
     */
    public function submitted(SubmittedRequest $request): JsonResponse
    {
        $hashId = $request->input('id');
        $hashids = new Hashids();
        $ids = $hashids->decode($hashId);
        if (empty($ids)) {
            $id = $ids[0];
            $order = PayOrderService::findById($id);
            if ($order->pay_status == PayStatus::NORMAL) {
                PayOrderService::update($id, [
                    'pay_status' => PayStatus::SUBMITED
                ]);
            }
        }
        return $this->success();
    }
}
