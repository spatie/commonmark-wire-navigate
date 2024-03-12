<?php

namespace Spatie\CommonMarkWireNavigate;

use Closure;
use Spatie\Url\Url;

class ShouldWireNavigate
{
    public static function from(Closure|array|null $resolver): callable
    {
        if (is_null($resolver)) {
            return fn () => true;
        }

        if (is_callable($resolver)) {
            return $resolver;
        }

        return function (Url $url) {
            return false;
        };
    }
}
