<?php

namespace Spatie\CommonMarkWireNavigate;

use Spatie\Url\Url;

class ShouldWireNavigate
{
    public static function make(
        string $domain = '',
        ?array $paths = null,
    ): callable {
        $baseUrl = $domain
            ? Url::fromString(preg_match('/^https?:\/\//', $domain) ? $domain : ('https://'.$domain))
            : Url::create();

        return function (string $url) use ($baseUrl, $paths) {
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

            if (is_null($paths)) {
                return true;
            }

            if (is_array($paths)) {
                foreach ($paths as $path) {
                    if (str_starts_with(trim($url->getPath(), '/'), trim($path, '/'))) {
                        return true;
                    }
                }
            }

            return false;
        };
    }
}
