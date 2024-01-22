<?php

declare(strict_types=1);

namespace App\Enums;

use BenSampo\Enum\Enum;

/**
 * @method static static OptionOne()
 * @method static static OptionTwo()
 * @method static static OptionThree()
 */
final class CouponDefine extends Enum
{
    const EndOfUsesage = 0;

    const Discount = 1;

    const Price = 0;
}
