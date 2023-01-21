<?php

namespace App\Services\Admin;

use App\Enums\Fields\ApplicationStatus;
use App\Enums\Fields\CallbackStatus;
use App\Enums\Fields\CheckStatus;
use App\Models\Application;
use App\Services\Admin\AdminUserService;
use Exception;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use JetBrains\PhpStorm\ArrayShape;

class UploadService
{
    /**
     * @param UploadedFile $file
     * @return string
     */
    #[ArrayShape(['url' => "false|string"])]
    public static function store(UploadedFile $file): string
    {
        return $file->store('tmp/' . now()->format('Y/m/d'), ['disk'=>'public']);
    }
}
