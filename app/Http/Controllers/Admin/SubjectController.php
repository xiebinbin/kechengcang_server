<?php

namespace App\Http\Controllers\Admin;

use App\Enums\SortActionTypeEnum;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Subject\ChangeSortRequest;
use App\Http\Requests\Admin\Subject\DestroyRequest;
use App\Http\Requests\Admin\Subject\IndexRequest;
use App\Http\Requests\Admin\Subject\OptionsDataRequest;
use App\Http\Requests\Admin\Subject\ShowRequest;
use App\Http\Requests\Admin\Subject\StoreRequest;
use App\Http\Requests\Admin\Subject\UpdateRequest;
use App\Services\Base\ChannelService;
use App\Services\Base\SubjectService;
use Exception;
use Illuminate\Http\JsonResponse;

class SubjectController extends Controller
{
    public function index(IndexRequest $request): JsonResponse
    {
        $page = $request->integer('page');
        $limit = $request->integer('limit');
        $params = $request->all(['online_status', 'channel_id']);
        $paginator = SubjectService::list($page, $limit, $params);
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
        $item = SubjectService::findById($id);
        return $this->success($item->toArray());
    }

    /**
     * @param StoreRequest $request
     * @return JsonResponse
     * @throws Exception
     */
    public function store(StoreRequest $request): JsonResponse
    {
        $input = $request->all(['title', 'icon_url', 'online_status', 'channel_id']);
        $item = SubjectService::create($input);
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
        SubjectService::update($id, $input);
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
        SubjectService::delete($id);
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
        SubjectService::changeSort($id, $sortActionType);
        return $this->success();
    }

    /**
     * @param OptionsDataRequest $request
     * @return JsonResponse
     */
    public function optionsData(OptionsDataRequest $request): JsonResponse
    {
        $input = $request->all(['channel_id']);
        $data = SubjectService::optionsData($input);
        return $this->success($data);
    }
}
