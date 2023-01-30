<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\EditorJs\UploadFileRequest;
use App\Http\Requests\Admin\EditorJs\UploadImageRequest;
use App\Http\Requests\Admin\Upload\IndexRequest;
use App\Services\Admin\UploadService;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Storage;

class EditorJsController extends Controller
{
    public function uploadImage(UploadImageRequest $request): JsonResponse
    {
        $file = $request->file('image');
        $url = UploadService::store($file);
        return response()->json([
            'success' => 1,
            'file' => [
                'url' => Storage::disk('doge')->url($url)
            ]
        ]);
    }

    public function uploadFile(UploadFileRequest $request): JsonResponse
    {
        $file = $request->file('file');
        $url = UploadService::store($file);
        return response()->json([
            'success' => 1,
            'file' => [
                'url' => Storage::disk('doge')->url($url)
            ]
        ]);
    }
}
