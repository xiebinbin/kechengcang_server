<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Http\Requests\Web\Subject\IndexRequest;
use App\Services\Web\ChannelService;
use App\Services\Web\SubjectService;
use Illuminate\Http\JsonResponse;

class SubjectController extends Controller
{
    public function index(IndexRequest $request): JsonResponse
    {
        $channelId = intval($request->input('channel_id', 0));
        $items = SubjectService::all($channelId);
        return $this->success($items);
    }

    public function tree(IndexRequest $request): JsonResponse
    {
        $channelId = intval($request->input('channel_id', 0));
        $items = SubjectService::tree($channelId);
        return $this->success($items);
    }
}
