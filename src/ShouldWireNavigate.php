<?php

namespace Spatie\CommonMarkWireNavigate;

use Spatie\Url\Url;

class ShouldWireNavigate
{
    protected Url $baseUrl;

    public function __construct(
        protected string $domain = '',
        protected ?array $paths = null,
    ) {
        $this->baseUrl = $domain
            ? Url::fromString(preg_match('/^https?:\/\//', $domain) ? $domain : ('https://'.$domain))
            : Url::create();
    }

    public function __invoke(string $url): bool
    {
        $url = Url::fromString($url);

        // Ensure hosts match
        if ($url->getHost()) {
            if (strtolower($url->getHost()) !== strtolower($this->baseUrl->getHost())) {
                return false;
            }
        }

        // Ensure base paths match
        if (! str_starts_with(strtolower($url->getPath()), strtolower($this->baseUrl->getPath()))) {
            return false;
        }

        if (is_null($this->paths)) {
            return true;
        }

        if (is_array($this->paths)) {
            foreach ($this->paths as $path) {
                if (str_starts_with(trim($url->getPath(), '/').'/', trim($path, '/').'/')) {
                    return true;
                }
            }
        }

        return false;
    }
}
