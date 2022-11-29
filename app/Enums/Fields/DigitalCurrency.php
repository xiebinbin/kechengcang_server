<?php declare(strict_types=1);

namespace App\Enums\Fields;

use BenSampo\Enum\Attributes\Description;
use BenSampo\Enum\Enum;

/**
 * @method static static NORMAL()
 * @method static static ERC20_USDT()
 * @method static static TRC20_USDT()
 * @method static static ERC20_MATIC()
 * @method static static ERC20_FTM()
 */
final class DigitalCurrency extends Enum
{
    #[Description('未知')]
    const NORMAL = 1;
    #[Description('ERC20/USDT')]
    const ERC20_USDT = 2;
    #[Description('TRC20/USDT')]
    const TRC20_USDT = 3;
    #[Description('ERC20/MATIC')]
    const ERC20_MATIC = 4;
    #[Description('ERC20/FTM')]
    const ERC20_FTM = 5;
}
