<?php

namespace App\Services\Base;

use App\Enums\Fields\OnlineStatus;
use App\Enums\SortActionTypeEnum;
use App\Models\Category;
use App\Models\Channel;
use App\Models\Subject;
use Exception;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class CategoryService
{
    /**
     * @return array
     */
    public static function optionsData(): array
    {
        return Category::query()->get([DB::raw('name AS text'), DB::raw('id AS value')])->toArray();
    }

    /**
     * @return array
     */
    public static function treeData(): array
    {
        $channels = Channel::query()->get([
            DB::raw('id AS value'),
            DB::raw('name AS title')
        ])->toArray();
        $subjects = Subject::query()->get([
            'channel_id',
            DB::raw('id AS value'),
            DB::raw('name AS title')
        ]);
        $categories = Category::query()->get([
            'subject_id',
            DB::raw('id AS value'),
            DB::raw('name AS title')
        ]);
        foreach ($channels as &$channel) {
            $children = $subjects->where('channel_id', $channel['value'])->map(function ($subject) use (&$categories, &$channel) {
                $item = $subject->toArray();
                unset($item['channel_id']);
                $children = $categories->where('subject_id', $item['value'])->map(function ($category) use (&$channel, &$item) {
                    unset($category['subject_id']);
                    $category['value'] = $channel['value'] . '-' . $item['value'] . '-' . $category['value'];
                    $category['selectable'] = true;
                    return $category;
                })->values()->toArray();
                if (!empty($children)) {
                    $item['children'] = $children;
                }
                $item['selectable'] = false;
                $item['value'] = $channel['value'] . '-' . $item['value'] . '-0';
                return $item;
            })->values()->toArray();
            if (!empty($children)) {
                $channel['children'] = $children;
            }
            $channel['selectable'] = false;
            $channel['value'] = $channel['value'] . '-0-0';
        }
        return $channels;
    }

    /**
     * @param int $page
     * @param int $limit
     * @param array $params
     * @return LengthAwarePaginator
     */
    public static function list(int $page, int $limit, array $params = []): LengthAwarePaginator
    {
        $query = Category::query()->with(['channel', 'subject']);
        $cols = ['id', 'name', 'channel_id', 'subject_id', 'online_status', 'updated_at'];
        if (!empty($params['online_status'])) {
            $query->where('online_status', $params['online_status']);
        }
        return $query->latest('sort')->paginate($limit, $cols, 'page', $page);
    }

    /**
     * @throws Exception
     */
    public static function create(array $data): Category
    {
        $item = new Category();
        $item->name = $data['name'];
        $item->channel_id = $data['channel_id'];
        $item->subject_id = $data['subject_id'];
        $lastItem = Channel::query()->orderBy('sort', 'DESC')->first();
        $item->sort = empty($lastItem) ? 1 : ($lastItem->sort + 1);
        $item->online_status = $data['online_status'] ?? OnlineStatus::UP;
        $item->course_number = 0;
        $item->save();
        Subject::query()->where('id', $item->subject_id)->increment('category_number');
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
            throw new Exception('该分类不存在');
        }
        if (isset($data['name'])) {
            $item->name = $data['name'];
        }
        if (isset($data['channel_id'])) {
            $item->channel_id = $data['channel_id'];
        }
        if (isset($data['subject_id'])) {
            $item->subject_id = $data['subject_id'];
            if ($item->subject_id != $item->getOriginal('subject_id')) {
                Subject::query()->where('id', $item->subject_id)->increment('category_number');
                Subject::query()->where('id', $item->getOriginal('subject_id'))->decrement('category_number');
            }
        }
        if (isset($data['online_status'])) {
            $item->online_status = $data['online_status'];
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
            throw new Exception('该分类不存在');
        }
        if ($item->course_number > 0) {
            throw new Exception('该分类存在课程,不可删除!');
        }
        return $item->delete();
    }

    /**
     * @param int $id
     * @return Model|null
     */
    public static function findById(int $id): ?Category
    {
        return Category::query()->find($id);
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
            throw new Exception('频道不存在!');
        }
        if (Category::query()->count() <= 0) {
            return true;
        }
        if ($sortActionType->is(SortActionTypeEnum::TOP())) {
            Category::query()
                ->where('sort', $item->sort + 1)
                ->decrement('sort');
            $item->increment('sort');
        } else if ($sortActionType->is(SortActionTypeEnum::DOWN())) {
            Category::query()
                ->where('sort', $item->sort - 1)
                ->increment('sort');
            $item->decrement('sort');
        } else if ($sortActionType->is(SortActionTypeEnum::STICKY_TOP())) {
            $topItem = Category::query()->orderBy('sort', 'DESC')->first();
            Category::query()->where('sort', '>', $item->sort)->decrement('sort');
            $item->sort = $topItem->sort;
            $item->save();
        } else if ($sortActionType->is(SortActionTypeEnum::STICKY_BOTTOM())) {
            $bottomItem = Category::query()->orderBy('sort', 'ASC')->first();
            Category::query()->where('sort', '<', $item->sort)->increment('sort');
            $item->sort = $bottomItem->sort;
            $item->save();
            $bottomItem->save();
        }
        return true;
    }
}
