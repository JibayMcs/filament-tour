# This is my package filament-tour

[![Latest Version on Packagist](https://img.shields.io/packagist/v/jibaymcs/filament-tour.svg?style=flat-square)](https://packagist.org/packages/jibaymcs/filament-tour)
[![GitHub Tests Action Status](https://img.shields.io/github/actions/workflow/status/jibaymcs/filament-tour/run-tests.yml?branch=main&label=tests&style=flat-square)](https://github.com/jibaymcs/filament-tour/actions?query=workflow%3Arun-tests+branch%3Amain)
[![GitHub Code Style Action Status](https://img.shields.io/github/actions/workflow/status/jibaymcs/filament-tour/fix-php-code-style-issues.yml?branch=main&label=code%20style&style=flat-square)](https://github.com/jibaymcs/filament-tour/actions?query=workflow%3A"Fix+PHP+code+style+issues"+branch%3Amain)
[![Total Downloads](https://img.shields.io/packagist/dt/jibaymcs/filament-tour.svg?style=flat-square)](https://packagist.org/packages/jibaymcs/filament-tour)



This is where your description should go. Limit it to a paragraph or two. Consider adding a small example.

## Installation

You can install the package via composer:

```bash
composer require jibaymcs/filament-tour
```

You can publish and run the migrations with:

```bash
php artisan vendor:publish --tag="filament-tour-migrations"
php artisan migrate
```

You can publish the config file with:

```bash
php artisan vendor:publish --tag="filament-tour-config"
```

Optionally, you can publish the views using

```bash
php artisan vendor:publish --tag="filament-tour-views"
```

This is the contents of the published config file:

```php
return [
];
```

## Usage

```php
$filamentTour = new JibayMcs\FilamentTour();
echo $filamentTour->echoPhrase('Hello, JibayMcs!');
```

## Testing

```bash
composer test
```

## Changelog

Please see [CHANGELOG](CHANGELOG.md) for more information on what has changed recently.

## Contributing

Please see [CONTRIBUTING](.github/CONTRIBUTING.md) for details.

## Security Vulnerabilities

Please review [our security policy](../../security/policy) on how to report security vulnerabilities.

## Credits

- [JibayMcs](https://github.com/JibayMcs)
- [All Contributors](../../contributors)

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
