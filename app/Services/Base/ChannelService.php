<?php

namespace App\Services\Base;

use App\Enums\Fields\OnlineStatus;
use App\Enums\SortActionTypeEnum;
use App\Models\Channel;
use Exception;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class ChannelService
{

    /**
     * @return array
     */
    public static function optionsData(): array
    {
        return Channel::query()->get([DB::raw('name AS text'), DB::raw('id AS value')])->toArray();
    }

    /**
     * @param int $page
     * @param int $limit
     * @param array $params
     * @return LengthAwarePaginator
     */
    public static function list(int $page, int $limit, array $params = []): LengthAwarePaginator
    {
        $query = Channel::query();
        $cols = ['id', 'name', 'online_status', 'updated_at'];
        if (!empty($params['online_status'])) {
            $query->where('online_status', $params['online_status']);
        }
        return $query->latest('sort')->paginate($limit, $cols, 'page', $page);
    }

    /**
     * @throws Exception
     */
    public static function create(array $data): Channel
    {
        $item = new Channel();
        $item->name = $data['name'];
        $lastItem = Channel::query()->orderBy('sort', 'DESC')->first();
        $item->sort = empty($lastItem) ? 1 : ($lastItem->sort + 1);
        $item->online_status = $data['online_status'] ?? OnlineStatus::UP;
        $item->subject_number = 0;
        $item->save();
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
            throw new Exception('该频道不存在');
        }
        if (isset($data['name'])) {
            $item->name = $data['name'];
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
            throw new Exception('该频道不存在');
        }
        if ($item->subject_number > 0) {
            throw new Exception('该频道存在栏目,不可删除!');
        }
        return $item->delete();
    }

    /**
     * @param int $id
     * @return Model|null
     */
    public static function findById(int $id): ?Channel
    {
        return Channel::query()->find($id);
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
        if (Channel::query()->count() <= 0) {
            return true;
        }
        if ($sortActionType->is(SortActionTypeEnum::TOP())) {
            Channel::query()
                ->where('sort', $item->sort + 1)
                ->decrement('sort');
            $item->increment('sort');
        } else if ($sortActionType->is(SortActionTypeEnum::DOWN())) {
            Channel::query()
                ->where('sort', $item->sort - 1)
                ->increment('sort');
            $item->decrement('sort');
        } else if ($sortActionType->is(SortActionTypeEnum::STICKY_TOP())) {
            $topItem = Channel::query()->orderBy('sort', 'DESC')->first();
            Channel::query()->where('sort', '>', $item->sort)->decrement('sort');
            $item->sort = $topItem->sort;
            $item->save();
        } else if ($sortActionType->is(SortActionTypeEnum::STICKY_BOTTOM())) {
            $bottomItem = Channel::query()->orderBy('sort', 'ASC')->first();
            Channel::query()->where('sort', '<', $item->sort)->increment('sort');
            $item->sort = $bottomItem->sort;
            $item->save();
            $bottomItem->save();
        }
        return true;
    }
}
