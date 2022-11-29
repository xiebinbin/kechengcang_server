<?php declare(strict_types=1);

namespace App\Enums\Fields;

use BenSampo\Enum\Attributes\Description;
use BenSampo\Enum\Enum;

/**
 * @method static static NORMAL()
 * @method static static FINISH()
 */
final class OrderStatus extends Enum
{
    #[Description('未完成')]
    const NORMAL = 1;
    #[Description('已完成')]
    const FINISH = 2;
}
