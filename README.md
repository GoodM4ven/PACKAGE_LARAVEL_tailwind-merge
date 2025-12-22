<div align="center">بسم الله الرحمن الرحيم</div>
<div align="left">

# Tailwind Merge

[![Latest Version on Packagist](https://img.shields.io/packagist/v/goodm4ven/tailwind-merge.svg?style=for-the-badge&color=gray)](https://packagist.org/packages/goodm4ven/tailwind-merge)
[![GitHub Tests Action Status](https://img.shields.io/github/actions/workflow/status/goodm4ven/PACKAGE_LARAVEL_tailwind-merge/pest.yml?branch=main&label=tests&style=for-the-badge&color=forestgreen)](https://github.com/goodm4ven/PACKAGE_LARAVEL_tailwind-merge/actions?query=workflow%3Apest+branch%3Amain)
[![Coverage Status](https://img.shields.io/codecov/c/github/goodm4ven/PACKAGE_LARAVEL_tailwind-merge/main?style=for-the-badge&color=purple)](https://codecov.io/gh/goodm4ven/PACKAGE_LARAVEL_tailwind-merge)
[![Total Downloads](https://img.shields.io/packagist/dt/goodm4ven/tailwind-merge.svg?style=for-the-badge&color=blue)](https://packagist.org/packages/goodm4ven/tailwind-merge)

<img src="./.github/images/banner.png">

Dealing with TailwindCSS classes overriding can either be done with fighting important (`!`) classes, **OR** by using this package to remove the conflicting ones from the targetted component and keep the outside (passed) one instead.


## Installation

Install the package with [`Composer`](https://getcomposer.org/):

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
\GoodMaven\TailwindMerge\TailwindMerge::classes('last conflicting classes win');
```

- Attribute bag macro inside Laravel Blade components
```php
$attributes->twMerge('last conflicting classes win', 'then last conflicting classes win');
```

- Blade directive for Blade views in general
```php
@twMerge('last conflicting classes win')
```


## Development

This package was initiated based on my [Laravel package template](https://github.com/goodm4ven/TEMPLATE_PACKAGE_TALL/blob/main/README.md#development) that is built on top of [Spatie's](https://github.com/spatie/package-skeleton-laravel). Make sure to read the docs for both.


## Support

Support ongoing package maintenance as well as the development of **other projects** through [sponsorship](https://github.com/sponsors/GoodM4ven) or one-time [donations](https://github.com/sponsors/GoodM4ven?frequency=one-time&sponsor=GoodM4ven) if you prefer.

### Credits
- Inspired by the [original package](https://github.com/gehrisandro/tailwind-merge-laravel)
- [Blade Formatter](https://github.com/shufo/blade-formatter)
- [Pest](https://github.com/pestphp/pest-plugin-laravel)
- [Playwrite](https://playwrite.dev)
- [PHP](https://php.net)
- [TailwindCSS](https://tailwindcss.com)
- [Laravel](https://laravel.com)
- [ChatGPT & Codex](https://developers.openai.com/codex)
- [GoodM4ven](https://github.com/GoodM4ven)
- [All Contributors](../../contributors)

</div>
<br>
<div align="center">والحمد لله رب العالمين</div>
