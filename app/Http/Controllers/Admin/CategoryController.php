<?php

namespace App\Http\Controllers\Admin;

use App\Enums\SortActionTypeEnum;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Category\ChangeSortRequest;
use App\Http\Requests\Admin\Category\DestroyRequest;
use App\Http\Requests\Admin\Category\IndexRequest;
use App\Http\Requests\Admin\Category\ShowRequest;
use App\Http\Requests\Admin\Category\StoreRequest;
use App\Http\Requests\Admin\Category\UpdateRequest;
use App\Services\Base\CategoryService;
use Exception;
use Illuminate\Http\JsonResponse;

class CategoryController extends Controller
{
    public function index(IndexRequest $request): JsonResponse
    {
        $page = $request->integer('page');
        $limit = $request->integer('limit');
        $params = $request->all(['online_status']);
        $paginator = CategoryService::list($page, $limit, $params);
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
        $item = CategoryService::findById($id);
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
        $item = CategoryService::create($input);
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
        CategoryService::update($id, $input);
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
        CategoryService::delete($id);
        return $this->success();
    }


    /**
     * @param ChangeSortRequest $request
     * @return JsonResponse
     * @throws Exception
     */
    public function changeSort(ChangeSortRequest $request): JsonResponse
    {
        $id = $request->integer('id');
        $sortActionType = SortActionTypeEnum::fromValue($request->integer('sort_action_type'));
        CategoryService::changeSort($id, $sortActionType);
        return $this->success();
    }

    /**
     * @return JsonResponse
     */
    public function optionsData(): JsonResponse
    {
        $data = CategoryService::optionsData();
        return $this->success($data);
    }
    public function treeData():JsonResponse {
        $data = CategoryService::treeData();
        return $this->success($data);
    }
}
