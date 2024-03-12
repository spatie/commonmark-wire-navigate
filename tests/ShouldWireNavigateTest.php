<?php

use Spatie\CommonMarkWireNavigate\ShouldWireNavigate;

it('can always returns true when null', function () {
    expect(ShouldWireNavigate::from(null)())->toBe(true);
});

it('can pass along a closure', function () {
    $closure = fn () => false;
    $shouldWireNavigate = ShouldWireNavigate::from($closure);

    expect($shouldWireNavigate)->toBe($closure);
    expect($shouldWireNavigate())->toBe(false);
});
