<?php

namespace App\Services\Base;

use App\Enums\Fields\OnlineStatus;
use App\Enums\Fields\RecommendStatus;
use App\Enums\SortActionTypeEnum;
use App\Models\Category;
use App\Models\Course;
use Exception;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
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
        return Course::search('', function (Indexes $meilisearch, string $query, array $options) {
            $options['sort'] = ['sort:desc'];
            return $meilisearch->search($query, $options);
        })->paginate($limit, 'page', $page);
    }

    /**
     * @throws Exception
     */
    public static function create(array $data): Course
    {
        $item = new Course();
        $item->title = $data['title'];
        $item->intro = $data['intro'] ?? '';
        $item->detail = $data['detail'] ?? [];
        $item->catalogue = $data['catalogue'] ?? [];
        $item->resources = $data['resources'] ?? [];
        $item->category_ids = $data['category_ids'] ?? [];
        $item->cover_url = $data['cover_url'] ?? '';
        $item->recommend_content = $data['recommend_content'] ?? '';
        $lastItem = Course::query()->orderBy('sort', 'DESC')->first();
        $item->sort = empty($lastItem) ? 1 : ($lastItem->sort + 1);
        $item->recommend_status = $data['recommend_status'] ?? RecommendStatus::OFF;
        $item->online_status = $data['online_status'] ?? OnlineStatus::UP;
        $item->save();
        if (!empty($item->category_ids)) {
            // 解析出category_ids
            $categoryIds = [];
            foreach ($item->category_ids as $categoryId) {
                $categoryIds[] = explode('-', $categoryId)[2];
            }
            Category::query()->whereIn('id', $categoryIds)->increment('course_number');
        }
        return $item;
    }

    /**
     * @param int $id
     * @param array $data
     * @return bool
     * @throws Exception
     */
    public static function update(int $id, array $data): bool
    {
        $item = self::findById($id);
        if (empty($item)) {
            throw new Exception('该课程不存在');
        }
        if (isset($data['title'])) {
            $item->title = $data['title'];
        }
        if (isset($data['recommend_content'])) {
            $item->recommend_content = $data['recommend_content'];
        }
        $item->intro = $data['intro'] ?? '';
        if (isset($data['intro'])) {
            $item->intro = $data['intro'] ?? '';
        }
        if (isset($data['detail'])) {
            $item->detail = $data['detail'] ?? '';
        }
        if (isset($data['catalogue'])) {
            $item->catalogue = $data['catalogue'] ?? '';
        }
        if (isset($data['resources'])) {
            $item->resources = $data['resources'] ?? [];
        }
        if (isset($data['cover_url'])) {
            $item->cover_url = $data['cover_url'] ?? '';
        }
        if (isset($data['category_ids'])) {
            $item->category_ids = $data['category_ids'] ?? [];

            $oldCategoryIdsa = $item->getOriginal('category_ids');
            $oldCategoryIds = [];
            foreach ($oldCategoryIdsa as $categoryId) {
                $oldCategoryIds[] = explode('-', $categoryId)[2];
            }
            $newCategoryIds = array_diff($item->category_ids, $oldCategoryIds);
            $delCategoryIds = array_diff($oldCategoryIds, $item->category_ids);
            // 对比新旧
            if (!empty($delCategoryIds)) {
                Category::query()->whereIn('id', $delCategoryIds)->decrement('course_number');
            }
            if (!empty($newCategoryIds)) {
                Category::query()->whereIn('id', $newCategoryIds)->increment('course_number');
            }
        }
        if (!empty($data['online_status'])) {
            $item->online_status = $data['online_status'];
        }
        if (!empty($data['recommend_status'])) {
            $item->recommend_status = $data['recommend_status'];
        }
        return $item->save();
    }

    /**
     * @param int $id
     * @return bool|null
     * @throws Exception
     */
    public static function delete(int $id): ?bool
    {
        $item = self::findById($id);
        if (empty($item)) {
            throw new Exception('该课程不存在');
        }
        if (!empty($item->category_ids)) {
            Category::query()->whereIn('id', $item->category_ids)->decrement('course_number');
        }
        return $item->delete();
    }

    /**
     * @param int $id
     * @return Model|null
     */
    public static function findById(int $id): ?Course
    {
        return Course::query()->find($id);
    }

    /**
     * @param int $id
     * @param SortActionTypeEnum $sortActionType
     * @return bool
     * @throws Exception
     */
    public static function changeSort(int $id, SortActionTypeEnum $sortActionType): bool
    {
        $item = self::findById($id);
        if (empty($item)) {
            throw new Exception('课程不存在!');
        }
        if (Course::query()->count() <= 0) {
            return true;
        }
        if ($sortActionType->is(SortActionTypeEnum::TOP())) {
            $courses = Course::query()->where('sort', $item->sort + 1)->get();
            $courseIds = $courses->pluck('id')->toArray();
            Course::query()->whereIn('id', $courseIds)->decrement('sort');
            Course::query()->whereIn('id', $courseIds)->unsearchable();
            Course::query()->whereIn('id', $courseIds)->searchable();
            $item->increment('sort');
        } else if ($sortActionType->is(SortActionTypeEnum::DOWN())) {
            $courses = Course::query()->where('sort', $item->sort - 1)->get();
            $courseIds = $courses->pluck('id')->toArray();
            Course::query()->whereIn('id', $courseIds)->increment('sort');
            Course::query()->whereIn('id', $courseIds)->unsearchable();
            Course::query()->whereIn('id', $courseIds)->searchable();
            $item->decrement('sort');
        } else if ($sortActionType->is(SortActionTypeEnum::STICKY_TOP())) {
            $topItem = Course::query()->orderBy('sort', 'DESC')->first();
            $courses = Course::query()->where('sort', '>', $item->sort)->get();
            $courseIds = $courses->pluck('id')->toArray();
            Course::query()->whereIn('id', $courseIds)->decrement('sort');
            Course::query()->whereIn('id', $courseIds)->unsearchable();
            Course::query()->whereIn('id', $courseIds)->searchable();
            $item->sort = $topItem->sort;
            $item->save();
        } else if ($sortActionType->is(SortActionTypeEnum::STICKY_BOTTOM())) {
            $bottomItem = Course::query()->orderBy('sort', 'ASC')->first();
            $courses = Course::query()->where('sort', '<', $item->sort)->get();
            $courseIds = $courses->pluck('id')->toArray();
            Course::query()->whereIn('id', $courseIds)->increment('sort');
            Course::query()->whereIn('id', $courseIds)->unsearchable();
            Course::query()->whereIn('id', $courseIds)->searchable();
            $item->sort = $bottomItem->sort;
            $item->save();
            $bottomItem->save();
        }

        return true;
    }
}
