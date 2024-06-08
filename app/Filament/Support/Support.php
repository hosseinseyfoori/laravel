<?php

namespace App\Filament\Support;

use Closure;
use Illuminate\Support\HtmlString;

class Support
{
    public static function formatPrice($price): HtmlString
    {
        $formattedPrice = number_format($price, 0, '.', ',');
        return new HtmlString(chunk_split($formattedPrice, 3, ','));
    }
}
