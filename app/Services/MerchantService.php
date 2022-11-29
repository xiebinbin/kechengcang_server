<?php

namespace App\Services;

use App\Enums\Fields\MerchantStatus;
use App\Models\Merchant;
use Exception;
use Illuminate\Database\Eloquent\Model;

class MerchantService
{

    /**
     * @throws Exception
     */
    public static function create(array $data): Merchant
    {
        $item = new Merchant();
        $item->admin_user_id = $data['admin_user_id'];
        $item->remark = $data['remark'] ?? '';
        $item->status = $data['status'] ?? MerchantStatus::ENABLE;
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
            throw new Exception('该邮箱商户不存在');
        }
        if (!empty($data['remark']) && $item->remark != $data['remark']) {
            $item->remark = $data['remark'];
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
            throw new Exception('该商户不存在');
        }
        return $item->delete();
    }


    /**
     * @param int $id
     * @return Model|null
     */
    public static function findById(int $id): ?Merchant
    {
        return Merchant::query()->find($id);
    }
}
