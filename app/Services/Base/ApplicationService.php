<?php

namespace App\Services\Base;

use App\Enums\Fields\ApplicationStatus;
use App\Enums\Fields\CallbackStatus;
use App\Enums\Fields\CheckStatus;
use App\Models\Application;
use App\Services\Admin\AdminUserService;
use Exception;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class ApplicationService
{
    /**
     * @param int $page
     * @param int $limit
     * @param array $params
     * @return LengthAwarePaginator
     */
    public static function list(int $page, int $limit, array $params = []): LengthAwarePaginator
    {
        $query = Application::query();
        $cols = ['id', 'name', 'status', 'check_status', 'created_at'];
        if (!empty($params['status'])) {
            $query->where('status', $params['status']);
        }
        if (!empty($params['check_status'])) {
            $query->where('check_status', $params['check_status']);
        }
        if (!empty($params['admin_user_id'])) {
            $query->where('admin_user_id', $params['admin_user_id']);
        }
        return $query->latest()->paginate($limit, $cols, 'page', $page);
    }

    /**
     * @throws Exception
     */
    public static function create(array $data): Application
    {
        $item = new Application();
        $adminUser = AdminUserService::findById($data['admin_user_id']);
        if (empty($adminUser)) {
            throw new Exception('对应账户不存在!');
        }
        $item->admin_user_id = $data['admin_user_id'];
        $item->name = $data['name'];
        $item->remark = $data['remark'] ?? '';
        $item->app_id = Str::random(64);
        $item->secure_key = Str::random(64);
        $item->check_status = $data['check_status'] ?? CallbackStatus::NORMAL;
        $item->status = $data['status'] ?? ApplicationStatus::ENABLE;
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
        if (!empty($data['name']) && $item->name != $data['name']) {
            $item->name = $data['name'];
        }
        if (!empty($data['remark']) && $item->remark != $data['remark']) {
            $item->remark = $data['remark'];
        }
        if (!empty($data['check_status'])) {
            $item->check_status = CheckStatus::fromValue($data['check_status']);
        }
        if (!empty($data['status'])) {

            $item->status = ApplicationStatus::fromValue($data['status']);
        }
        if (!empty($data['app_id']) && $item->app_id != $data['app_id']) {
            $item->app_id = $data['app_id'];
        }
        if (!empty($data['secure_key']) && $item->secure_key != $data['secure_key']) {
            $item->secure_key = $data['secure_key'];
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
            throw new Exception('该应用不存在');
        }
        return $item->delete();
    }

    /**
     * @param int $id
     * @return Model|null
     */
    public static function findById(int $id): ?Application
    {
        return Application::query()->find($id);
    }

    /**
     * @param string $appId
     * @return Model|null
     */
    public static function findByAppId(string $appId): ?Application
    {
        return Application::query()->where('app_id', $appId)->first();
    }

    /**
     * @param string $secureKey
     * @param string $salt
     * @return string
     */
    public static function sign(string $secureKey, string $salt): string
    {
        return md5(md5($salt) . md5($secureKey));
    }

    /**
     * @param string $secureKey
     * @param string $salt
     * @return string
     */
    public static function checkSign(string $secureKey, string $sign, string $salt): string
    {
        return $sign == self::sign($secureKey, $salt);
    }
}
