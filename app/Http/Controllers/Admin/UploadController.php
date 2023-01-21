<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Upload\IndexRequest;
use App\Services\Admin\UploadService;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Storage;

class UploadController extends Controller
{
    public function index(IndexRequest $request): JsonResponse
    {
        $file = $request->file('file');
        $url = UploadService::store($file);
        return response()->json([
            'code' => 200,
            'message' => 'ok',
            'url' => Storage::disk('public')->url($url)
        ]);
    }
}
