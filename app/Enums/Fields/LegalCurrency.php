<?php declare(strict_types=1);

namespace App\Enums\Fields;

use BenSampo\Enum\Attributes\Description;
use BenSampo\Enum\Enum;

/**
 * @method static static USD()
 * @method static static CNY()
 */
final class LegalCurrency extends Enum
{
    #[Description('美元')]
    const USD = 1;
    #[Description('人民币')]
    const CNY = 2;
}
