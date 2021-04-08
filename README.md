# middlewares/lowercase

[![Latest Version on Packagist][ico-version]][link-packagist]
[![Software License][ico-license]](LICENSE)
![Testing][ico-ga]
[![Total Downloads][ico-downloads]][link-downloads]

Middleware to set the uri path to lowercase. For example, ``/Foo/Bar/`` is converted to ``/foo/bar``. Useful if you define your routes as lowercase and want to make your routes case insensitive.

## Requirements

* PHP >= 7.2
* A [PSR-7 http library](https://github.com/middlewares/awesome-psr15-middlewares#psr-7-implementations)
* A [PSR-15 middleware dispatcher](https://github.com/middlewares/awesome-psr15-middlewares#dispatcher)

## Installation

This package is installable and autoloadable via Composer as [middlewares/lowercase](https://packagist.org/packages/middlewares/lowercase).

```sh
composer require middlewares/lowercase
```

## Example

```php
$dispatcher = new Dispatcher([
    (new Middlewares\Lowercase())
        ->redirect()
        ->attribute('before-lowercase-uri')
]);

$response = $dispatcher->dispatch(new ServerRequest());
```

## Usage

### redirect

If the path must be converted to lowercase, this option returns a 301 response redirecting to the new lowercase path. Optionally, you can provide a Psr\Http\Message\ResponseFactoryInterface that will be used to create the redirect response. If it's not defined, Middleware\Utils\Factory will be used to detect it automatically.

```php
$responseFactory = new MyOwnResponseFactory();

//Simply set the path to lowercase
$lowercase = new Middlewares\Lowercase();

//Returns a redirect response to the new path
$lowercase = (new Middlewares\Lowercase())->redirect();

//Returns a redirect response to the new path using a specific response factory
$lowercase = (new Middlewares\Lowercase())->redirect($responseFactory);
```

### attribute

If the path must be converted to lowercase, this method will store the original path in an atrribute.

```php
// Save the original non-lowercase uri in the custom attribute "pre-lowercase-path"
$lowercase = (new Middlewares\Lowercase())->attribute('before-lowercase-uri');
```

---

Please see [CHANGELOG](CHANGELOG.md) for more information about recent changes and [CONTRIBUTING](CONTRIBUTING.md) for contributing details.

The MIT License (MIT). Please see [LICENSE](LICENSE) for more information.

[ico-version]: https://img.shields.io/packagist/v/middlewares/lowercase.svg?style=flat-square
[ico-license]: https://img.shields.io/badge/license-MIT-brightgreen.svg?style=flat-square
[ico-ga]: https://github.com/middlewares/minifier/workflows/testing/badge.svg
[ico-downloads]: https://img.shields.io/packagist/dt/middlewares/lowercase.svg?style=flat-square

[link-packagist]: https://packagist.org/packages/middlewares/lowercase
[link-downloads]: https://packagist.org/packages/middlewares/lowercase
