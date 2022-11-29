<?php declare(strict_types=1);

namespace App\Enums\Fields;

use BenSampo\Enum\Attributes\Description;
use BenSampo\Enum\Enum;

/**
 * @method static static MERCHANT()
 * @method static static SUPER()
 */
final class Role extends Enum
{
    #[Description('超级用户')]
    const SUPER = 1;
    #[Description('商户')]
    const MERCHANT = 2;
}
