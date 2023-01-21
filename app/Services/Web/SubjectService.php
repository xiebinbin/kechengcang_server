<?php

namespace App\Services\Web;

use App\Models\Category;
use App\Models\Subject;

class SubjectService
{

    /**
     * @param int $channelId
     * @return array
     */
    public static function all(int $channelId): array
    {
        return Subject::query()
            ->where('channel_id', $channelId)->latest('sort')->get(['id', 'name', 'icon_url'])->toArray();

    }

    /**
     * @param int $channelId
     * @return array
     */
    public static function tree(int $channelId): array
    {
        $subjects = Subject::query()->where('channel_id', $channelId)->latest('sort')->get(['id', 'name']);
        $categories = Category::query()->whereIn('channel_id', $subjects->pluck('id')->toArray())->get(['id', 'name', 'subject_id']);
        foreach ($subjects as &$subject) {
            $subject->categories = $categories->where('subject_id', $subject->id)->map(function ($item) {
                return [
                    'id' => $item->id,
                    'name' => $item->name
                ];
            })->values()->toArray();
        }
        return $subjects->toArray();
    }
}
