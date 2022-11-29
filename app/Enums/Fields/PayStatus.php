<?php declare(strict_types=1);

namespace App\Enums\Fields;

use BenSampo\Enum\Attributes\Description;
use BenSampo\Enum\Enum;

/**
 * @method static static NORMAL()
 * @method static static FINISH()
 * @method static static SUBMITED()
 * @method static static TIMEOUT()
 */
final class PayStatus extends Enum
{
    #[Description('未支付')]
    const NORMAL = 1;
    #[Description('已提交')]
    const SUBMITED = 2;
    #[Description('支付完成')]
    const FINISH = 3;
    #[Description('支付超时')]
    const TIMEOUT = 4;
}
