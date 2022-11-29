<?php

namespace App\Services\Base;

use App\Enums\Fields\CallbackStatus;
use App\Enums\Fields\DigitalCurrency;
use App\Enums\Fields\LegalCurrency;
use App\Enums\Fields\OrderStatus;
use App\Enums\Fields\PayStatus;
use App\Models\PayOrder;
use Exception;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Model;

class PayOrderService
{
    /**
     * @param int $page
     * @param int $limit
     * @param array $params
     * @return LengthAwarePaginator
     */
    public static function list(int $page, int $limit, array $params = []): LengthAwarePaginator
    {
        $query = PayOrder::query();

        $cols = ['id', 'title', 'admin_user_id', 'application_id', 'fee', 'pay_fee', 'pay_status', 'status', 'callback_status', 'created_at', 'callback_at', 'pay_submit_at', 'pay_finish_at'];
        if (!empty($params['status'])) {
            $query->where('status', $params['status']);
        }
        if (!empty($params['pay_status'])) {
            $query->where('pay_status', $params['pay_status']);
        }
        if (!empty($params['callback_status'])) {
            $query->where('callback_status', $params['callback_status']);
        }
        if (!empty($params['admin_user_id'])) {
            $query->where('admin_user_id', $params['admin_user_id']);
        }
        if (!empty($params['application_id'])) {
            $query->where('application_id', $params['application_id']);
        }
        return $query->latest()->paginate($limit, $cols, 'page', $page);
    }

    /**
     * @throws Exception
     */
    public static function create(array $data): PayOrder
    {
        $item = new PayOrder();
        $item->title = $data['title'];
        $item->admin_user_id = $data['admin_user_id'];
        $item->application_id = $data['application_id'];
        $item->remark = $data['remark'] ?? '';
        $item->fee = $data['fee'] ?? 0;
        $item->currency = $data['currency'] ?? LegalCurrency::USD;
        $item->pay_fee = $data['pay_fee'] ?? 0;
        $item->pay_currency = $data['pay_currency'] ?? DigitalCurrency::ERC20_MATIC;
        $item->pay_account = '';
        $item->receipt_account = $data['receipt_account'];
        $item->callback_url = $data['callback_url'] ?? '';
        $item->redirect_url = $data['redirect_url'] ?? '';
        $item->pay_status = PayStatus::NORMAL;
        $item->status = OrderStatus::NORMAL;
        $item->callback_status = CallbackStatus::NORMAL;
        $item->save();
        return $item;
    }

    /**
     * @param int $id
     * @param array $data
     * @return bool
     * @throws Exception
     */
    public static function update(int $id, array $data): bool
    {
        $item = self::findById($id);
        if (empty($item)) {
            throw new Exception('该订单不存在');
        }
        if (!empty($data['pay_fee']) && $item->pay_fee != $data['pay_fee']) {
            $item->pay_fee = $data['pay_fee'];
        }
        if (!empty($data['pay_currency']) && $item->pay_currency != $data['pay_currency']) {
            $item->pay_currency = $data['pay_currency'];
        }
        if (!empty($data['pay_account']) && $item->pay_account != $data['pay_account']) {
            $item->pay_account = $data['pay_account'];
        }
        if (!empty($data['receipt_account']) && $item->receipt_account != $data['receipt_account']) {
            $item->receipt_account = $data['receipt_account'];
        }
        if (!empty($data['pay_status']) && $item->pay_status != $data['pay_status']) {
            $item->pay_status = $data['pay_status'];
        }
        if (!empty($data['status']) && $item->status != $data['status']) {
            $item->status = $data['status'];
        }
        if (!empty($data['callback_status']) && $item->callback_status != $data['callback_status']) {
            $item->callback_status = $data['callback_status'];
        }
        if (!empty($data['callback_at']) && $item->callback_at != $data['callback_at']) {
            $item->callback_at = $data['callback_at'];
        }
        if (!empty($data['callback_times']) && $item->callback_times != $data['callback_times']) {
            $item->callback_times = $data['callback_times'];
        }
        if (!empty($data['pay_finish_at']) && $item->pay_finish_at != $data['pay_finish_at']) {
            $item->pay_finish_at = $data['pay_finish_at'];
        }
        if (!empty($data['pay_refresh_at']) && $item->pay_refresh_at != $data['pay_refresh_at']) {
            $item->pay_refresh_at = $data['pay_refresh_at'];
        }
        if (!empty($data['pay_submit_at']) && $item->pay_submit_at != $data['pay_submit_at']) {
            $item->pay_submit_at = $data['pay_submit_at'];
        }
        return $item->save();
    }

    /**
     * @param int $id
     * @return bool|null
     * @throws Exception
     */
    public static function delete(int $id): ?bool
    {
        $item = self::findById($id);
        if (empty($item)) {
            throw new Exception('该订单不存在');
        }
        return $item->delete();
    }

    /**
     * @param int $id
     * @return Model|null
     */
    public static function findById(int $id): ?PayOrder
    {
        return PayOrder::query()->with(['admin_user','application'])->find($id);
    }
}
