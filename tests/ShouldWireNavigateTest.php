<?php

use Spatie\CommonMarkWireNavigate\ShouldWireNavigate;

it('is enabled on links on the same domain or without a domain when no base URL is provided', function () {
    $shouldWireNavigate = ShouldWireNavigate::make();

    expect($shouldWireNavigate('/'))->toBe(true);
    expect($shouldWireNavigate('/about'))->toBe(true);

    expect($shouldWireNavigate('https://example.app'))->toBe(false);
    expect($shouldWireNavigate('https://example.app/about'))->toBe(false);
});

it('is enabled on links on the same domain or without a domain when a base URL is provided', function (string $baseUrl) {
    $shouldWireNavigate = ShouldWireNavigate::make($baseUrl);

    expect($shouldWireNavigate('/'))->toBe(true);
    expect($shouldWireNavigate('/about'))->toBe(true);

    expect($shouldWireNavigate('https://example.app'))->toBe(true);
    expect($shouldWireNavigate('https://example.app/about'))->toBe(true);

    expect($shouldWireNavigate('https://another.app'))->toBe(false);
    expect($shouldWireNavigate('https://another.app/about'))->toBe(false);
})->with(['https://example.app', 'example.app']);

it('is enabled on links matching a path when an array of paths are provided', function () {
    $shouldWireNavigate = ShouldWireNavigate::make('example.app', ['docs']);

    expect($shouldWireNavigate('/'))->toBe(false);
    expect($shouldWireNavigate('/docs'))->toBe(true);
    expect($shouldWireNavigate('/docs/installation'))->toBe(true);
});

it('accepts a closure for custom logic', function () {
    $shouldWireNavigate = ShouldWireNavigate::make('', fn (string $url) => $url === '/about');

    expect($shouldWireNavigate('/about'))->toBe(true);
    expect($shouldWireNavigate('/contact'))->toBe(false);
});
