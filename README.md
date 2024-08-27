# Add a wire:navigate attribute to links rendered in Markdown

[![Latest Version on Packagist](https://img.shields.io/packagist/v/spatie/commonmark-wire-navigate.svg?style=flat-square)](https://packagist.org/packages/spatie/commonmark-wire-navigate)
[![Tests](https://img.shields.io/github/actions/workflow/status/spatie/commonmark-wire-navigate/run-tests.yml?branch=main&label=tests&style=flat-square)](https://github.com/spatie/commonmark-wire-navigate/actions/workflows/run-tests.yml)
[![Total Downloads](https://img.shields.io/packagist/dt/spatie/commonmark-wire-navigate.svg?style=flat-square)](https://packagist.org/packages/spatie/commonmark-wire-navigate)

An extension for [league/commonmark](https://github.com/thephpleague/commonmark) to add a `wire:navigate` attribute to links rendered in Markdown and enable [SPA mode](https://livewire.laravel.com/docs/navigate) in Livewire.

## Support us

[<img src="https://github-ads.s3.eu-central-1.amazonaws.com/commonmark-wire-navigate.jpg?t=1" width="419px" />](https://spatie.be/github-ad-click/commonmark-wire-navigate)

We invest a lot of resources into creating [best in class open source packages](https://spatie.be/open-source). You can support us by [buying one of our paid products](https://spatie.be/open-source/support-us).

We highly appreciate you sending us a postcard from your hometown, mentioning which of our package(s) you are using. You'll find our address on [our contact page](https://spatie.be/about-us). We publish all received postcards on [our virtual postcard wall](https://spatie.be/open-source/postcards).

## Installation

You can install the package via composer:

```bash
composer require spatie/commonmark-wire-navigate
```

## Usage

Register `CommonMarkWireNavigate` as a CommonMark extension.

```php
use League\CommonMark\Environment\Environment;
use League\CommonMark\CommonMarkConverter;
use Spatie\CommonMarkWireNavigate\WireNavigateExtension;

$converter = new CommonMarkConverter($environment);
$converter->getEnvironment()->addExtension(new WireNavigateExtension());

echo $converter->convert('[About](/about)');
// <p><a href="/about" wire:navigate>About</a></p>
```

For more information on CommonMark extensions and environments, refer to the [CommonMark documentation](https://commonmark.thephpleague.com/2.4/basic-usage/).

### Laravel-markdown

When using the [Laravel-markdown](https://github.com/spatie/laravel-markdown/) package, you may register the extension in `config/markdown.php`:

```php
/*
 * These extensions should be added to the markdown environment. A valid
 * extension implements League\CommonMark\Extension\ExtensionInterface
 *
 * More info: https://commonmark.thephpleague.com/2.4/extensions/overview/
 */
'extensions' => [
    Spatie\CommonMarkWireNavigate\WireNavigateExtension::class,
],
```

### Choosing which links to enhance

By default, the extension will add `wire:navigate` to all internal links except fragments of the current page. To know which link is internal, you must specify your application's base URL.

```php
$converter = new CommonMarkConverter($environment);
$converter->getEnvironment()->addExtension(new WireNavigateExtension(
    baseUrl: 'https://example.app',
));


echo $converter->convert('[About](/about)');
// <p><a href="/about" wire:navigate>About</a>

echo $converter->convert('[About](https://example.app/about)');
// <p><a href="https://example.app/about" wire:navigate>About</a>

echo $converter->convert('[Twitter](https://twitter.com/spatie_be)');
// <a href="https://twitter.com/spatie_be">Twitter</a></p>
```

Additionally, you can configure whether the attribute will be added using an array of patterns or a callback.

Using an array to specify a root path in your application:

```php
$converter = new CommonMarkConverter($environment);
$converter->getEnvironment()->addExtension(new WireNavigateExtension(
    baseUrl: 'https://example.app',
    enabled: ['docs', 'guide'],
));

echo $converter->convert('[Installation](/docs/installation)');
// <p><a href="/docs/installation" wire:navigate>Installation</a>

echo $converter->convert('[Guide](/guide)');
// <p><a href="/guide" wire:navigate>Guide</a>

echo $converter->convert('[About](/about)');
// <p><a href="/about">About</a>
```

Using a callback:

```php
$converter = new CommonMarkConverter($environment);
$converter->getEnvironment()->addExtension(new WireNavigateExtension(
    baseUrl: 'https://example.app',
    enabled: fn (string $url) => preg_match('/\/docs\//', $url),
    hover: true, 
));
```

Enable on fragments of the current page:

```php
$converter = new CommonMarkConverter($environment);
$converter->getEnvironment()->addExtension(new WireNavigateExtension(
    fragment: true, 
));
```

### Prefetching pages on hover

If you want to have Livewire prefetch pages when a link is hovered, enable the `hover` option.

```php
$converter = new CommonMarkConverter($environment);
$converter->getEnvironment()->addExtension(new WireNavigateExtension(
    baseUrl: 'https://example.app',
    hover: true, 
));
```

Now the extension will add `wire:navigate.hover` to links instead.

```php
echo $converter->convert('[About](/about)');
// <p><a href="/about" wire:navigate.hover>About</a></p>
```

## Testing

```bash
composer test
```

## Changelog

Please see [CHANGELOG](CHANGELOG.md) for more information on what has changed recently.

## Contributing

Please see [CONTRIBUTING](https://github.com/spatie/.github/blob/main/CONTRIBUTING.md) for details.

## Security Vulnerabilities

Please review [our security policy](../../security/policy) on how to report security vulnerabilities.

## Credits

- [Sebastian De Deyne](https://github.com/sebastiandedeyne)
- [All Contributors](../../contributors)

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
