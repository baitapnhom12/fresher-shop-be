<?php

declare(strict_types=1);

namespace App\Enums;

use BenSampo\Enum\Enum;

/**
 * @method static static OptionOne()
 * @method static static OptionTwo()
 * @method static static OptionThree()
 */
final class ProductDefine extends Enum
{
    const CeaseSales = 0;

    const OpenSales = 1;

    const SortByDefault = 0;

    const SortByUpdatedLastest = 1;

    const SortBySold = 2;

    const SortByReviewesDesc = 3;

    const SortByPriceAsc = 4;

    const SortByPriceDesc = 5;

    const SortByAvgStarDesc = 6;

    const sortBy = [
        0 => 'created_at',
        1 => 'updated_at',
        2 => 'quantity_sold',
        3 => 'reviews_count',
        5 => 'min_price',
        6 => 'average_rating',
    ];
}
