<?php

namespace App\Http\Controllers\Admin;

use App\Enums\SortActionTypeEnum;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Course\ChangeSortRequest;
use App\Http\Requests\Admin\Course\IndexRequest;
use App\Http\Requests\Admin\Course\ShowRequest;
use App\Http\Requests\Admin\Course\StoreRequest;
use App\Http\Requests\Admin\Course\UpdateRequest;
use App\Services\Base\CourseService;
use Exception;
use Illuminate\Http\JsonResponse;

class CourseController extends Controller
{
    public function index(IndexRequest $request): JsonResponse
    {
        $page = $request->integer('page');
        $limit = $request->integer('limit');
        $params = $request->all(['online_status', 'recommend_status']);
        $paginator = CourseService::list($page, $limit, $params);
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
        $item = CourseService::findById($id);
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
        $item = CourseService::create($input);
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
        CourseService::update($id, $input);
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
        CourseService::changeSort($id, $sortActionType);
        return $this->success();
    }

}
