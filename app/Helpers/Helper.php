<?php

namespace App\Helpers;

class Helper
{
    public static function getCurrentShopName($path)
    {
        $pattern = "#api\/(\w+)\/(.|\n)*#";
        $matches = [];
        preg_match($pattern, $path, $matches);
        return $matches[1];
    }
}
