<?php

declare(strict_types=1);

namespace App\Enums;

use BenSampo\Enum\Enum;

/**
 * @method static static OptionOne()
 * @method static static OptionTwo()
 * @method static static OptionThree()
 */
final class PostDefine extends Enum
{
    const Hidden = 0;

    const Active = 1;

    const Unpopular = 0;

    const Popular = 1;
}
