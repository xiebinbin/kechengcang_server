<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Http\JsonResponse;
use Illuminate\Routing\Controller as BaseController;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    /**
     * @param array|null $data
     * @return JsonResponse
     */
    public function success(?array $data = null): JsonResponse
    {
        return response()->json([
            'code' => 200,
            'message' => 'ok',
            'result' => $data
        ]);
    }
}
