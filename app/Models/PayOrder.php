<?php

namespace App\Models;

use App\Enums\Fields\CallbackStatus;
use App\Enums\Fields\DigitalCurrency;
use App\Enums\Fields\LegalCurrency;
use App\Enums\Fields\OrderStatus;
use App\Enums\Fields\PayStatus;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class PayOrder extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $casts = [
        'created_at' => 'datetime:Y-m-d H:i:s',
        'updated_at' => 'datetime:Y-m-d H:i:s',
        'status' => OrderStatus::class,
        'pay_status' => PayStatus::class,
        'callback_status' => CallbackStatus::class,
        'currency' => LegalCurrency::class,
        'pay_currency' => DigitalCurrency::class,
    ];

    /**
     * @return BelongsTo
     */
    public function admin_user(): BelongsTo
    {
        return $this->belongsTo(AdminUser::class);
    }

    /**
     * @return BelongsTo
     */
    public function application(): BelongsTo
    {
        return $this->belongsTo(Application::class);
    }
}
