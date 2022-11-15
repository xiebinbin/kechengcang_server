<?php declare(strict_types=1);

namespace App\Enums\Fields;

use BenSampo\Enum\Attributes\Description;
use BenSampo\Enum\Enum;

/**
 * @method static static UP()
 * @method static static DOWN()
 */
final class OnlineStatus extends Enum
{
    #[Description('上线')]
    const UP = 1;
    #[Description('下线')]
    const DOWN = 2;
}
