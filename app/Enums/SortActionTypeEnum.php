<?php declare(strict_types=1);

namespace App\Enums;

use BenSampo\Enum\Attributes\Description;
use BenSampo\Enum\Enum;

/**
 * @method static static TOP()
 * @method static static DOWN()
 * @method static static STICKY_TOP()
 * @method static static STICKY_BOTTOM()
 */
final class SortActionTypeEnum extends Enum
{
    #[Description('上移')]
    const TOP = 1;
    #[Description('下移')]
    const DOWN = 2;
    #[Description('置顶')]
    const STICKY_TOP = 3;
    #[Description('置底')]
    const STICKY_BOTTOM = 4;
}
