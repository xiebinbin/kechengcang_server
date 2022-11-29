<?php

namespace App\Http\Controllers\Admin;

use App\Enums\Fields\Role;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Application\IndexRequest;
use App\Http\Requests\Admin\Application\ShowRequest;
use App\Services\Base\PayOrderService;
use Illuminate\Http\JsonResponse;

class PayOrderController extends Controller
{
    public function index(IndexRequest $request): JsonResponse
    {
        $page = $request->integer('page');
        $limit = $request->integer('limit');
        $params = $request->all(['pay_status', 'callback_status', 'admin_user_id', 'application_id']);
        $user = $request->user();
        if ($user->role != Role::SUPER) {
            $params['admin_user_id'] = $user->id;
        }
        $paginator = PayOrderService::list($page, $limit, $params);
        return $this->success([
            'total' => $paginator->total(),
            'items' => $paginator->items(),
            'last_page' => $paginator->lastPage(),
            'current_page' => $paginator->currentPage(),
            'per_page' => $paginator->perPage(),
            'params' => $params,
        ]);
    }

    /**
     * @param ShowRequest $request
     * @return JsonResponse
     */
    public function show(ShowRequest $request): JsonResponse
    {
        $id = $request->integer('id');
        $item = PayOrderService::findById($id);

        $user = $request->user();
        if ($user->role == Role::MERCHANT && $item->admin_user_id != $user->id) {
            return $this->success();
        }
        return $this->success($item->toArray());
    }
}
