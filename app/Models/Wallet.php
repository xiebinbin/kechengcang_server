<?php

namespace App\Models;

use App\Enums\Fields\DigitalCurrency;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class Wallet extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $casts = [
        'created_at' => 'datetime:Y-m-d H:i:s',
        'updated_at' => 'datetime:Y-m-d H:i:s',
        'digital_currency' => DigitalCurrency::class,
    ];

    /**
     * @return BelongsTo
     */
    public function admin_user(): BelongsTo
    {
        return $this->belongsTo(AdminUser::class);
    }
}
