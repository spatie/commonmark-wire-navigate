<?php

namespace Spatie\CommonMarkWireNavigate;

use Closure;
use Spatie\Url\Url;

class ShouldWireNavigate
{
    public static function from(?string $baseUrl, Closure|array|null $resolver): callable
    {
        $baseUrl = Url::fromString($baseUrl ?? '');

        return function (string $url) use ($baseUrl, $resolver) {
            $url = Url::fromString($url);

            // Ensure hosts match
            if ($url->getHost()) {
                if (strtolower($url->getHost()) !== strtolower($baseUrl->getHost())) {
                    return false;
                }
            }

            // Ensure base paths match
            if (! str_starts_with(strtolower($url->getPath()), strtolower($baseUrl->getPath()))) {
                return false;
            }

            if (is_null($resolver)) {
                return true;
            }

            if (is_callable($resolver)) {
                return $resolver((string) $url);
            }

            if (is_array($resolver)) {
                foreach ($resolver as $path) {
                    // @todo
                }
            }

            return false;
        };
    }
}
