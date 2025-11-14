# Resolves conflicting TailwindCSS classes in Laravel Blade components

[![Latest Version on Packagist](https://img.shields.io/packagist/v/goodm4ven/tailwind-merge.svg?style=flat-square)](https://packagist.org/packages/goodm4ven/tailwind-merge)
[![GitHub Tests Action Status](https://img.shields.io/github/actions/workflow/status/goodm4ven/tailwind-merge/run-tests.yml?branch=main&label=tests&style=flat-square)](https://github.com/goodm4ven/tailwind-merge/actions?query=workflow%3Arun-tests+branch%3Amain)
[![GitHub Code Style Action Status](https://img.shields.io/github/actions/workflow/status/goodm4ven/tailwind-merge/fix-php-code-style-issues.yml?branch=main&label=code%20style&style=flat-square)](https://github.com/goodm4ven/tailwind-merge/actions?query=workflow%3A"Fix+PHP+code+style+issues"+branch%3Amain)
[![Total Downloads](https://img.shields.io/packagist/dt/goodm4ven/tailwind-merge.svg?style=flat-square)](https://packagist.org/packages/goodm4ven/tailwind-merge)

Dealing with TailwindCSS classes overriding can either be done with fighting important (`!`) classes, or by using this package to remove the conflicting ones from the targetted component and keep the outside (passed) one instead.


## Installation

You can install the package via [composer](https://getcomposer.org/):

```bash
composer require goodm4ven/tailwind-merge
```


## Usage

**It's all about the last-wins approach for consistency. Single string or multiple ones are around as arguments. You may also add them as an associative array to conditions!**

- Global helper function for PHP anywhere
```php
twMerge('text-lg text-sm'); // results in "text-sm"
twMerge('sm:text-lg', 'sm:text-3xl'); // results in "sm:text-3xl"
twMerge([
    'sm:text-lg py-10 px-5' => true,
    'sm:text-xl' => false,
    'sm:text-3xl py-5',
    'sm:text-sm' => true,
]); // results in "sm:text-sm px-5 py-5"
```

- Resolve the merger directly (container or facade)
```php
// Either
app('tailwind-merge')->classes('last conflicting classes win');
// Or
TailwindMerge::classes('last conflicting classes win');
```

- Attribute bag macro inside Laravel Blade components
```php
$attributes->twMerge('last conflicting classes win', 'then last conflicting classes win');
```

- Blade directive for Blade views in general
```php
@twMerge('last conflicting classes win')
```


## Testing

```bash
composer test
```


## Changelog

Please see [CHANGELOG](CHANGELOG.md) for more information on what has changed recently.


## Contributing

Please see [CONTRIBUTING](CONTRIBUTING.md) for details.


## Security Vulnerabilities

Please review [our security policy](../../security/policy) on how to report security vulnerabilities.


## Credits
- Inspired by the [original package](https://github.com/gehrisandro/tailwind-merge-laravel)
- [GoodM4ven](https://github.com/GoodM4ven)
- [All Contributors](../../contributors)


## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
