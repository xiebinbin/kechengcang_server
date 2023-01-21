<?php

namespace App\Models;

use App\Enums\Fields\OnlineStatus;
use App\Enums\Fields\RecommendStatus;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Laravel\Scout\Searchable;

class Course extends Model
{
    use Searchable;
    use HasFactory;
    use SoftDeletes;

    protected $casts = [
        'created_at' => 'datetime:Y-m-d H:i:s',
        'updated_at' => 'datetime:Y-m-d H:i:s',
        'category_ids' => 'array',
        'resources' => 'json',
        'detail' => 'json',
        'catalogue' => 'json',
        'online_status' => OnlineStatus::class,
        'recommend_status' => RecommendStatus::class
    ];

    /**
     * @return string
     */
    public function searchableAs(): string
    {
        return 'courses_index';
    }

    /**
     * @return array
     */
    public function toSearchableArray(): array
    {
        $array = $this->toArray();
        $categoryIds = [];
        $subjectIds = [];
        $channelIds = [];
        foreach ($array['category_ids'] as $categoryId) {
            $tmp = explode('-', $categoryId);
            $channelIds[] = intval($tmp[0]);
            $subjectIds[] = intval($tmp[1]);
            $categoryIds[] = intval($tmp[2]);
        }
        unset($array['detail'], $array['catalogue'], $array['resources']);
        $array['category_ids'] = $categoryIds;
        $array['subject_ids'] = array_unique($subjectIds);
        $array['channel_ids'] = array_unique($channelIds);
        return $array;
    }

    public function getScoutKey()
    {
        // 将id转化为hashid
        return $this->id;
    }

    public function getScoutKeyName(): string
    {
        return 'id';
    }

    /**
     * @return BelongsToMany
     */
    public function channels(): BelongsToMany
    {
        return $this->belongsToMany(Channel::class)->using(CategoryCourse::class);
    }

    /**
     * @return BelongsToMany
     */
    public function subjects(): BelongsToMany
    {
        return $this->belongsToMany(Subject::class)->using(CategoryCourse::class);
    }
}
