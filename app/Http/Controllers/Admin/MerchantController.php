<?php

namespace App\Http\Controllers\Admin;

use App\Enums\Fields\Role;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Merchant\DestroyRequest;
use App\Http\Requests\Admin\Merchant\IndexRequest;
use App\Http\Requests\Admin\Merchant\ShowRequest;
use App\Http\Requests\Admin\Merchant\StoreRequest;
use App\Http\Requests\Admin\Merchant\UpdateRequest;
use App\Services\Admin\AdminUserService;
use Exception;
use Illuminate\Http\JsonResponse;

class MerchantController extends Controller
{
    /**
     * @param IndexRequest $request
     * @return JsonResponse
     */
    public function index(IndexRequest $request): JsonResponse
    {
        $page = $request->integer('page');
        $limit = $request->integer('limit');
        $params = $request->all(['status']);
        $params['role'] = Role::MERCHANT;
        $paginator = AdminUserService::list($page, $limit, $params);
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
        $item = AdminUserService::findById($id);
        return $this->success($item->toArray());
    }

    /**
     * @param StoreRequest $request
     * @return JsonResponse
     * @throws Exception
     */
    public function store(StoreRequest $request): JsonResponse
    {
        $input = $request->all();
        $input['role'] = Role::MERCHANT;
        $item = AdminUserService::create($input);
        return $this->success($item->toArray());
    }

    /**
     * @param UpdateRequest $request
     * @return JsonResponse
     * @throws Exception
     */
    public function update(UpdateRequest $request): JsonResponse
    {
        $id = $request->integer('id');
        $input = $request->all();
        AdminUserService::update($id, $input);
        return $this->success();
    }

    /**
     * @param DestroyRequest $request
     * @return JsonResponse
     * @throws Exception
     */
    public function destroy(DestroyRequest $request): JsonResponse
    {
        $id = $request->integer('id');
        AdminUserService::delete($id);
        return $this->success();
    }
}
