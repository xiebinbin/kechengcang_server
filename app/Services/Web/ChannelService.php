<?php

namespace App\Services\Web;

use App\Models\Channel;

class ChannelService
{

    /**
     * @return array
     */
    public static function all(): array
    {
        return Channel::query()->latest('sort')->get(['id', 'name'])->toArray();
    }
}
