<?php declare(strict_types=1);

namespace App\Enums\Fields;

use BenSampo\Enum\Attributes\Description;
use BenSampo\Enum\Enum;

/**
 * @method static static ENABLE()
 * @method static static DISABLE()
 * @method static static LOCK()
 */
final class AdminUserStatus extends Enum
{
    #[Description('启用')]
    const ENABLE = 1;
    #[Description('禁用')]
    const DISABLE = 2;
    #[Description('锁定')]
    const LOCK = 3;
}
