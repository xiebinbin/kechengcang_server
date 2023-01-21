<?php

namespace App\Services\Base;

use App\Enums\Fields\OnlineStatus;
use App\Enums\SortActionTypeEnum;
use App\Models\Channel;
use App\Models\Subject;
use Exception;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class SubjectService
{
    /**
     * @param array $input
     * @return array
     */
    public static function optionsData(array $input): array
    {
        $query = Subject::query();
        if (!empty($input['channel_id'])) {
            $query->where('channel_id', $input['channel_id']);
        }
        return $query->get([DB::raw('name AS text'), DB::raw('id AS value')])->toArray();
    }

    /**
     * @param int $page
     * @param int $limit
     * @param array $params
     * @return LengthAwarePaginator
     */
    public static function list(int $page, int $limit, array $params = []): LengthAwarePaginator
    {
        $query = Subject::query()->with(['channel']);
        $cols = ['id', 'name', 'online_status', 'updated_at', 'channel_id'];
        if (!empty($params['online_status'])) {
            $query->where('online_status', $params['online_status']);
        }
        if (!empty($params['channel_id'])) {
            $query->where('channel_id', $params['channel_id']);
        }
        return $query->latest('sort')->paginate($limit, $cols, 'page', $page);
    }

    /**
     * @throws Exception
     */
    public static function create(array $data): Subject
    {
        $item = new Subject();
        $item->name = $data['name'] ?? '';
        $item->icon_url = $data['icon_url'] ?? '';
        $item->channel_id = $data['channel_id'];
        $lastItem = Subject::query()->orderBy('sort', 'DESC')->first();
        $item->sort = empty($lastItem) ? 1 : ($lastItem->sort + 1);
        $item->online_status = $data['online_status'] ?? OnlineStatus::UP;
        $item->category_number = 0;
        $item->save();
        Channel::query()->where('id', $item->channel_id)->increment('subject_number');
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
            throw new Exception('该栏目不存在');
        }
        if (isset($data['name'])) {
            $item->name = $data['name'];
        }
        if (isset($data['online_status'])) {
            $item->online_status = $data['online_status'];
        }
        if (isset($data['icon_url'])) {
            $item->icon_url = $data['icon_url'];
        }
        if (isset($data['channel_id'])) {
            $item->channel_id = $data['channel_id'];
            if ($item->channel_id != $item->getOriginal('channel_id')) {
                Channel::query()->where('id', $item->channel_id)->increment('subject_number');
                Channel::query()->where('id', $item->getOriginal('channel_id'))->decrement('subject_number');
            }
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
            throw new Exception('该栏目不存在');
        }
        if ($item->category_number > 0) {
            throw new Exception('该栏目存在分类,不可删除!');
        }
        Channel::query()->where('id', $item->channel_id)->decrement('subject_number');
        return $item->delete();
    }

    /**
     * @param int $id
     * @return Model|null
     */
    public static function findById(int $id): ?Subject
    {
        return Subject::query()->find($id);
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
            throw new Exception('栏目不存在!');
        }
        if (Subject::query()->count() <= 0) {
            return true;
        }
        if ($sortActionType->is(SortActionTypeEnum::TOP())) {
            Subject::query()
                ->where('sort', $item->sort + 1)
                ->decrement('sort');
            $item->increment('sort');
        } else if ($sortActionType->is(SortActionTypeEnum::DOWN())) {
            Subject::query()
                ->where('sort', $item->sort - 1)
                ->increment('sort');
            $item->decrement('sort');
        } else if ($sortActionType->is(SortActionTypeEnum::STICKY_TOP())) {
            $topItem = Subject::query()->orderBy('sort', 'DESC')->first();
            Subject::query()->where('sort', '>', $item->sort)->decrement('sort');
            $item->sort = $topItem->sort;
            $item->save();
        } else if ($sortActionType->is(SortActionTypeEnum::STICKY_BOTTOM())) {
            $bottomItem = Subject::query()->orderBy('sort', 'ASC')->first();
            Subject::query()->where('sort', '<', $item->sort)->increment('sort');
            $item->sort = $bottomItem->sort;
            $item->save();
            $bottomItem->save();
        }
        return true;
    }
}
