<?php

namespace App\Services\Admin;

use App\Models\AdminUser;
use Exception;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Model;

class AdminUserService
{
    /**
     * @param int $page
     * @param int $limit
     * @param array $params
     * @return LengthAwarePaginator
     */
    public static function list(int $page, int $limit, array $params = []): LengthAwarePaginator
    {
        $query = AdminUser::query();
        $cols = ['id', 'name','status', 'created_at', 'last_active_at'];
        if (!empty($params['status'])) {
            $query->where('status', $params['status']);
        }
        if (!empty($params['role'])) {
            $query->where('role', $params['role']);
        }
        return $query->latest()->paginate($limit, $cols, 'page', $page);
    }

    /**
     * @throws Exception
     */
    public static function create(array $data): AdminUser
    {
        $item = self::findByName($data['name']);
        if (!empty($item)) {
            throw new Exception('该账户已经存在');
        }
        $item = new AdminUser();
        $item->name = $data['name'];
        $item->role = $data['role'];
        $item->remark = $data['remark'] ?? '';
        $item->password = bcrypt($data['password']);
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
            throw new Exception('该账户不存在');
        }
        if (!empty($data['name']) && $item->name != $data['name']) {
            $old = self::findByName($data['name']);
            if (!empty($old) && $item->id != $old->id) {
                throw new Exception('该账户不存在');
            }
            $item->name = $data['name'];
        }
        if (isset($data['remark']) && $item->remark != $data['remark']) {
            $item->remark = $data['remark'];
        }
        if (!empty($data['password'])) {
            $item->password = bcrypt($data['password']);
        }
        if (!empty($data['status'])) {
            $item->status = $data['status'];
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
     * @param string $name
     * @return Model|null
     */
    public static function findByName(string $name): ?AdminUser
    {
        return AdminUser::query()->where('name', $name)->first();
    }

    /**
     * @param int $id
     * @return Model|null
     */
    public static function findById(int $id): ?AdminUser
    {
        return AdminUser::query()->find($id);
    }
}
