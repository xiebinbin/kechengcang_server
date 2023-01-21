<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Http\Requests\Web\Course\IndexRequest;
use App\Http\Requests\Web\Course\ShowRequest;
use App\Services\Web\CourseService;
use Illuminate\Http\JsonResponse;

class CourseController extends Controller
{
    public function index(IndexRequest $request): JsonResponse
    {
        $page = intval($request->input('page', 1));
        $limit = intval($request->input('limit', 4));
        $params = $request->all(['q', 'subject_ids', 'category_ids','sort_key', 'channel_ids', 'recommend_status']);
        $paginator = CourseService::list($page, $limit, $params);
        return $this->success([
            'total' => $paginator->total(),
            'items' => collect($paginator->items())->map(function ($item) {
                return [
                    'id' => $item->id,
                    'title' => $item->title,
                    'recommend_content' => $item->recommend_content,
                    'cover_url' => $item->cover_url,
                    'sort'=>$item->sort,
                    'recommend_status' => $item->recommend_status,
                ];
            }),
            'last_page' => $paginator->lastPage(),
            'current_page' => $paginator->currentPage(),
            'per_page' => $paginator->perPage(),
            'params' => $params,
        ]);
    }
    public function show(ShowRequest $request): JsonResponse
    {
        $id = intval($request->input('id', 1));
        $info = CourseService::info($id);
        return $this->success($info);
    }
}
