<?php

namespace App\Services\Admin;

use Exception;
use Illuminate\Http\UploadedFile;
use JetBrains\PhpStorm\ArrayShape;

class UploadService
{
    /**
     * @param UploadedFile $file
     * @return string
     * @throws Exception
     */
    #[ArrayShape(['url' => "false|string"])]
    public static function store(UploadedFile $file): string
    {
        return $file->store('tmp/' . now()->format('Y/m/d'), ['disk' => 'doge']);
    }
}
