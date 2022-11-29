<?php

namespace App\Services\Admin;

use Carbon\Carbon;
use Exception;
use JetBrains\PhpStorm\ArrayShape;

class AuthService
{
    /**
     * @param string $name
     * @param string $password
     * @param string $deviceName
     * @return array
     * @throws Exception
     */
    #[ArrayShape(['user' => "array", 'token' => "\Laravel\Sanctum\string|string"])]
    public static function login(string $name, string $password, string $deviceName): array
    {
        $item = AdminUserService::findByName($name);
        if (empty($item)) {
            throw new Exception('该账户不存在');
        }
        if (!password_verify($password, $item->password)) {
            throw new Exception('密码不正确');
        }
        $item->last_active_at = Carbon::now();
        $item->save();
        return [
            'user' => $item->toArray(),
            'token' => $item->createToken($deviceName)->plainTextToken
        ];
    }
}
