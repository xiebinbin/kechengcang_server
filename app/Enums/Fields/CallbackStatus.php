<?php declare(strict_types=1);

namespace App\Enums\Fields;

use BenSampo\Enum\Attributes\Description;
use BenSampo\Enum\Enum;

/**
 * @method static static NORMAL()
 * @method static static FINISH()
 * @method static static TIMEOUT()
 */
final class CallbackStatus extends Enum
{
    #[Description('未完成')]
    const NORMAL = 1;
    #[Description('已完成')]
    const FINISH = 2;
    #[Description('超时')]
    const TIMEOUT = 3;
}
