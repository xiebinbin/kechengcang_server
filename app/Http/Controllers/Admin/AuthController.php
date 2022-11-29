<?php

namespace App\Http\Controllers\Admin;

use App\Enums\Fields\Role;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Auth\LoginRequest;
use App\Services\Admin\AuthService;
use Exception;
use Illuminate\Http\JsonResponse;

class AuthController extends Controller
{
    /**
     * @param LoginRequest $request
     * @return JsonResponse
     * @throws Exception
     */
    public function login(LoginRequest $request): JsonResponse
    {
        $name = $request->input('name');
        $password = $request->input('password');
        $deviceName = $request->input('device_name');
        $data = AuthService::login($name, $password, $deviceName);
        $role = [
            'roleName' => '商户',
            'value' => 'merchant'
        ];
        if ($data['user']['role'] == Role::SUPER) {
            $role = [
                'roleName' => '管理员',
                'value' => 'super'
            ];;
        }
        return $this->success([
            'userId' => $data['user']['id'],
            'token' => $data['token'],
            'role' => $role
        ]);
    }

    /**
     * @return JsonResponse
     */
    public function me(): JsonResponse
    {
        $user = request()->user();
        $role = [
            'roleName' => '商户',
            'value' => 'merchant'
        ];
        if ($user->role == Role::SUPER) {
            $role = [
                'roleName' => '管理员',
                'value' => 'super'
            ];;
        }
        $user['roles'] = [$role];
        return $this->success([
            'id' => $user->id,
            'name' => $user->name,
            'roles' => [$role]
        ]);
    }

    /**
     * @return JsonResponse
     */
    public function logout(): JsonResponse
    {
        request()->user()->currentAccessToken()->delete();
        return $this->success();
    }
}
