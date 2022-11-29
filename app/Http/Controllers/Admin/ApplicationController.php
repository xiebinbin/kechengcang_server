<?php

namespace App\Http\Controllers\Admin;

use App\Enums\Fields\Role;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Application\DestroyRequest;
use App\Http\Requests\Admin\Application\IndexRequest;
use App\Http\Requests\Admin\Application\RefreshSecureKeyRequest;
use App\Http\Requests\Admin\Application\ShowRequest;
use App\Http\Requests\Admin\Application\StoreRequest;
use App\Http\Requests\Admin\Application\UpdateRequest;
use App\Services\Base\ApplicationService;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Str;

class ApplicationController extends Controller
{
    public function index(IndexRequest $request): JsonResponse
    {
        $page = $request->integer('page');
        $limit = $request->integer('limit');
        $params = $request->all(['status', 'check_status']);
        $user = $request->user();
        if ($user->role != Role::SUPER) {
            $params['admin_user_id'] = $user->id;
        }
        $paginator = ApplicationService::list($page, $limit, $params);
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
        $item = ApplicationService::findById($id);

        $user = $request->user();
        if ($user->role == Role::MERCHANT && $item->admin_user_id != $user->id) {
            return $this->success();
        }
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
        $user = $request->user();
        $input['admin_user_id'] = $user->id;
        $item = ApplicationService::create($input);
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
        $user = $request->user();
        if ($user->role != Role::SUPER) {
            $item = ApplicationService::findById($id);
            if ($item->admin_user_id != $user->id) {
                return $this->success();
            }
        }
        ApplicationService::update($id, $input);
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
        $user = $request->user();
        if ($user->role != Role::SUPER) {
            $item = ApplicationService::findById($id);
            if ($item->admin_user_id != $user->id) {
                return $this->success();
            }
        }
        ApplicationService::delete($id);
        return $this->success();
    }


    /**
     * @param RefreshSecureKeyRequest $request
     * @return JsonResponse
     * @throws Exception
     */
    public function refreshSecureKey(RefreshSecureKeyRequest $request): JsonResponse
    {
        $id = $request->integer('id');
        $user = $request->user();
        if ($user->role != Role::SUPER) {
            $item = ApplicationService::findById($id);
            if ($item->admin_user_id != $user->id) {
                return $this->success();
            }
        }
        ApplicationService::update($id, ['secure_key' => Str::random(64)]);
        return $this->success();
    }
}
