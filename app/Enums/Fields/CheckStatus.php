<?php declare(strict_types=1);

namespace App\Enums\Fields;

use BenSampo\Enum\Attributes\Description;
use BenSampo\Enum\Enum;

/**
 * @method static static WAIT()
 * @method static static PASS()
 * @method static static DENY()
 */
final class CheckStatus extends Enum
{
    #[Description('等待')]
    const WAIT = 1;
    #[Description('通过')]
    const PASS = 2;
    #[Description('拒绝')]
    const DENY = 3;
}
