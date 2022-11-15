<?php declare(strict_types=1);

namespace App\Enums\Fields;

use BenSampo\Enum\Attributes\Description;
use BenSampo\Enum\Enum;

/**
 * @method static static ON()
 * @method static static OFF()
 */
final class RecommendStatus extends Enum
{
    #[Description('开启')]
    const ON = 1;
    #[Description('关闭')]
    const OFF = 2;
}
