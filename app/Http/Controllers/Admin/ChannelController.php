<?php

namespace App\Http\Controllers\Admin;

use App\Enums\SortActionTypeEnum;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Channel\ChangeSortRequest;
use App\Http\Requests\Admin\Channel\DestroyRequest;
use App\Http\Requests\Admin\Channel\IndexRequest;
use App\Http\Requests\Admin\Channel\ShowRequest;
use App\Http\Requests\Admin\Channel\StoreRequest;
use App\Http\Requests\Admin\Channel\UpdateRequest;
use App\Services\Base\ChannelService;
use Exception;
use Illuminate\Http\JsonResponse;

class ChannelController extends Controller
{
    public function index(IndexRequest $request): JsonResponse
    {
        $page = $request->integer('page');
        $limit = $request->integer('limit');
        $params = $request->all(['online_status']);
        $paginator = ChannelService::list($page, $limit, $params);
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
        $item = ChannelService::findById($id);
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
        $item = ChannelService::create($input);
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
        $input = $request->all(['name', 'online_status']);
        ChannelService::update($id, $input);
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
        ChannelService::delete($id);
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
        ChannelService::changeSort($id, $sortActionType);
        return $this->success();
    }

    /**
     * @return JsonResponse
     */
    public function optionsData(): JsonResponse
    {
        $data = ChannelService::optionsData();
        return $this->success($data);
    }
}
