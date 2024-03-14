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
    $converter = new CommonMarkConverter();
    $converter->getEnvironment()->addExtension(new WireNavigateExtension(hover: true));

    expect(trim($converter->convert('[About](/about)')->getContent()))
        ->toBe('<p><a wire:navigate.hover href="/about">About</a></p>');
});

it('adds wire:navigate to links with path configuration', function () {
    $converter = new CommonMarkConverter();
    $converter->getEnvironment()->addExtension(new WireNavigateExtension(enabled: ['docs']));

    expect(trim($converter->convert('[About](/about)')->getContent()))
        ->toBe('<p><a href="/about">About</a></p>');

    expect(trim($converter->convert('[Installation](/docs/installation)')->getContent()))
        ->toBe('<p><a wire:navigate href="/docs/installation">Installation</a></p>');
});
