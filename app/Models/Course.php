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
        // 组织
        return $array;
    }

    public function getScoutKey()
    {
        return $this->id;
    }

    public function getScoutKeyName(): string
    {
        return 'id';
    }

    /**
     * @return HasMany
     */
    public function resources(): HasMany
    {
        return $this->hasMany(CourseResource::class);
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

    /**
     * @return BelongsToMany
     */
    public function categories(): BelongsToMany
    {
        return $this->belongsToMany(Category::class)->using(CategoryCourse::class);
    }
}
