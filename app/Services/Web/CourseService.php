<?php

namespace App\Services\Web;

use App\Enums\Fields\OnlineStatus;
use App\Models\Course;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use MeiliSearch\Endpoints\Indexes;

class CourseService
{

    /**
     * @param int $page
     * @param int $limit
     * @param array $params
     * @return LengthAwarePaginator
     */
    public static function list(int $page, int $limit, array $params = []): LengthAwarePaginator
    {
        $q = $params['q'] ?? '';
        $query = Course::search($q);
        if (!empty($params['sort_key'])) {
            if ($params['sort_key'] == 'recommend_status') {
                $query->orderBy($params['sort_key']);
            } else {
                $query->orderBy($params['sort_key'], 'desc');
            }
        }
        $query->where('online_status', OnlineStatus::UP);
        if (!empty($params['channel_ids'])) {
            $channelIds = collect(explode(',', $params['channel_ids']))->map(fn($val) => intval($val))->toArray();
            $query->whereIn('channel_ids', $channelIds);
        }
        if (!empty($params['subject_ids'])) {
            $subjectIds = collect(explode(',', $params['subject_ids']))->map(fn($val) => intval($val))->toArray();
            $query->whereIn('subject_ids', $subjectIds);
        }
        if (!empty($params['category_ids'])) {
            $categoryIds = collect(explode(',', $params['category_ids']))->map(fn($val) => intval($val))->toArray();
            $query->whereIn('category_ids', $categoryIds);
        }
        return $query->paginate($limit, 'page', $page);
    }

    /**
     * @param int $id
     * @return array|null
     */
    public static function info(int $id): array|null
    {
        $item = Course::query()->find($id);
        if (empty($item)) {
            return null;
        }
        return $item->toArray();
    }
}
