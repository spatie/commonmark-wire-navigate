<?php

use League\CommonMark\CommonMarkConverter;
use Spatie\CommonMarkWireNavigate\WireNavigateExtension;

it('adds wire:navigate to links', function () {
    $converter = new CommonMarkConverter();
    $converter->getEnvironment()->addExtension(new WireNavigateExtension());

    expect(trim($converter->convert('[About](/about)')->getContent()))
        ->toBe('<p><a wire:navigate href="/about">About</a></p>');
});

it('adds wire:navigate.hover to links', function () {
    $converter = new CommonMarkConverter([
        'wire_navigate' => [
            'hover' => true,
        ],
    ]);
    $converter->getEnvironment()->addExtension(new WireNavigateExtension());

    expect(trim($converter->convert('[About](/about)')->getContent()))
        ->toBe('<p><a wire:navigate.hover href="/about">About</a></p>');
});

it('adds wire:navigate to links with domain configuration', function () {
    $converter = new CommonMarkConverter([
        'wire_navigate' => [
            'domain' => 'myray.app',
        ],
    ]);
    $converter->getEnvironment()->addExtension(new WireNavigateExtension());

    expect(trim($converter->convert('[Installation](/docs/installation)')->getContent()))
        ->toBe('<p><a wire:navigate href="/docs/installation">Installation</a></p>');

    expect(trim($converter->convert('[Installation](https://myray.app/docs/installation)')->getContent()))
        ->toBe('<p><a wire:navigate href="https://myray.app/docs/installation">Installation</a></p>');

    expect(trim($converter->convert('[Spatie](https://spatie.be)')->getContent()))
        ->toBe('<p><a href="https://spatie.be">Spatie</a></p>');
});

it('adds wire:navigate to links with path configuration', function () {
    $converter = new CommonMarkConverter([
        'wire_navigate' => [
            'paths' => ['docs'],
        ],
    ]);
    $converter->getEnvironment()->addExtension(new WireNavigateExtension());

    expect(trim($converter->convert('[About](/about)')->getContent()))
        ->toBe('<p><a href="/about">About</a></p>');

    expect(trim($converter->convert('[Installation](/docs/installation)')->getContent()))
        ->toBe('<p><a wire:navigate href="/docs/installation">Installation</a></p>');
});
