# Tailwind Merger

[![Latest Version on Packagist](https://img.shields.io/packagist/v/goodm4ven/tailwind-merge.svg?style=flat-square)](https://packagist.org/packages/goodm4ven/tailwind-merge)
[![GitHub Tests Action Status](https://img.shields.io/github/actions/workflow/status/goodm4ven/PACKAGE_LARAVEL_tailwind-merge/run-tests.yml?branch=main&label=tests&style=flat-square)](https://github.com/goodm4ven/PACKAGE_LARAVEL_tailwind-merge/actions?query=workflow%3Arun-tests+branch%3Amain)
[![Total Downloads](https://img.shields.io/packagist/dt/goodm4ven/tailwind-merge.svg?style=flat-square)](https://packagist.org/packages/goodm4ven/tailwind-merge)

Dealing with TailwindCSS classes overriding can either be done with fighting important (`!`) classes, **OR** by using this package to remove the conflicting ones from the targetted component and keep the outside (passed) one instead.


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


## Development (Laravel Boost and MCP)

- Since the package is utilizing Orchestra Testbench for the testing environment, its configuration file [testbench.yaml](testbench.yaml) should be looked at.
- Running the MCP server is done with `./vendor/bin/testbench boost:mcp` instead of `php artisan boost:mcp` or optionally via VSC command prompts.
- (Optional): VSC users should have their MCP client pointing at `./vendor/bin/testbench boost:mcp`. (Check [.vscode/mcp.json](.vscode/mcp.json))
- (Optional): If you're using ChatGPT Codex in VSC or whatever, make sure your `config.toml` has at least the following:

### Editor MCP Configuration

Your editor's MCP entries in "config.toml" file shuold account for this as well:

```toml
# (Optional) For Laravel apps
[mcp_servers.laravel-boost]
command = "php"
args = ["artisan", "boost:mcp"]

# For 3rd party packages, including this
[mcp_servers.laravel-boost-package]
command = "./vendor/bin/testbench"
args = ["boost:mcp"]
```


## Testing

```bash
composer test
```


## Credits
- Inspired by the [original package](https://github.com/gehrisandro/tailwind-merge-laravel)
- [ChatGPT - Codex](https://developers.openai.com/codex)
- [GoodM4ven](https://github.com/GoodM4ven)
- [All Contributors](../../contributors)
