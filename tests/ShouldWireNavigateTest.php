<?php

use Spatie\CommonMarkWireNavigate\ShouldWireNavigate;

it('is enabled on links on the same domain or without a domain when no base URL is provided', function () {
    $shouldWireNavigate = new ShouldWireNavigate;

    expect($shouldWireNavigate('/'))->toBe(true);
    expect($shouldWireNavigate('/about'))->toBe(true);

    expect($shouldWireNavigate('https://example.app'))->toBe(false);
    expect($shouldWireNavigate('https://example.app/about'))->toBe(false);
});

it('is enabled on links on the same domain or without a domain when a base URL is provided', function (string $domain) {
    $shouldWireNavigate = new ShouldWireNavigate($domain);

    expect($shouldWireNavigate('/'))->toBe(true);
    expect($shouldWireNavigate('/about'))->toBe(true);

    expect($shouldWireNavigate('https://example.app'))->toBe(true);
    expect($shouldWireNavigate('https://example.app/about'))->toBe(true);

    expect($shouldWireNavigate('https://another.app'))->toBe(false);
    expect($shouldWireNavigate('https://another.app/about'))->toBe(false);
})->with(['https://example.app', 'example.app']);

it('is enabled on links matching a path when an array of paths are provided', function () {
    $shouldWireNavigate = new ShouldWireNavigate('example.app', ['docs']);

    expect($shouldWireNavigate('/'))->toBe(false);

    expect($shouldWireNavigate('/docs'))->toBe(true);
    expect($shouldWireNavigate('/docs/installation'))->toBe(true);

    expect($shouldWireNavigate('/docs-and-more'))->toBe(false);
});

it('is disabled on links on the same page when fragment is disabled (default)', function () {
    $shouldWireNavigate = new ShouldWireNavigate;

    expect($shouldWireNavigate('#example'))->toBe(false);
});

it('is enabled on links on the same page when fragment is enabled', function () {
    $shouldWireNavigate = new ShouldWireNavigate(fragment: true);

    expect($shouldWireNavigate('#example'))->toBe(true);
});

it('is enabled on links on the same domain or without a domain when fragment is disabled (default) when no base URL is provided', function () {
    $shouldWireNavigate = new ShouldWireNavigate;

    expect($shouldWireNavigate('/#example'))->toBe(true);
    expect($shouldWireNavigate('/about#example'))->toBe(true);

    expect($shouldWireNavigate('https://example.app#example'))->toBe(false);
    expect($shouldWireNavigate('https://example.app/about#example'))->toBe(false);
});

it('is enabled on links on the same page, domain, or without a domain when fragment is enabled and when a base URL is provided', function (string $domain) {
    $shouldWireNavigate = new ShouldWireNavigate(domain: $domain, fragment: true);

    expect($shouldWireNavigate('#example'))->toBe(true);
    expect($shouldWireNavigate('/#example'))->toBe(true);
    expect($shouldWireNavigate('/about#example'))->toBe(true);

    expect($shouldWireNavigate('https://example.app#example'))->toBe(true);
    expect($shouldWireNavigate('https://example.app/about#example'))->toBe(true);

    expect($shouldWireNavigate('https://another.app#example'))->toBe(false);
    expect($shouldWireNavigate('https://another.app/about#example'))->toBe(false);
})->with(['https://example.app', 'example.app']);
