<?php

use App\Enums\ImageDefine;

if (!function_exists('displayImageOrDefault')) {
    function displayImageOrDefault($images, $defaultImagePath = '/admin-layout/dist/img/photo3.jpg')
    {
        if (!empty($images)) {
            $image = collect($images)->first(function ($image) {
                return $image->main == ImageDefine::ImageMain;
            });

            if (!$image) {
                $image = collect($images)->first();
            }

            return $image->path ?? $defaultImagePath;
        } else {
            return $defaultImagePath;
        }
    }
}

if (!function_exists('displayProductPriceOrDefault')) {
    function displayProductPriceOrDefault($quantities)
    {
        if (!empty($quantities)) {
            $prices = [];
            foreach ($quantities as $quantity) {
                if ($quantity->price) {
                    $prices[] = $quantity->price;
                }
            }

            return min($prices) == max($prices) ? min($prices) : min($prices) . ' - ' . max($prices);
        } else {
            return '';
        }
    }
}
