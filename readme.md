# Laravel Markdown-Plus

A simple [Laravel 4](http://www.laravel.com) parser for markdown files with an optional meta-data section.

## Installation

Laravel Markdown-Plus can be installed via [composer](http://getcomposer.org) by requiring the `maxxscho/laravel-markdown-plus` package in your project's `composer.json`.

```json
{
    "require": {
        "maxxscho/laravel-markdown-plus": "~0"
    }
}
```

Next add the service provider and the alias to `app/config/app`.

```php
'providers' => [
    // ...
    'Maxxscho\LaravelMarkdownPlus\LaravelMarkdownPlusServiceProvider',
],

'aliases' => [
    // ..
    'MarkdownPlus' => 'Maxxscho\LaravelMarkdownPlus\Facade\LaravelMarkdownPlusFacade',
],
```


## Usage
#### Markdown file example

The meta section should be in [YAML](http://www.yaml.org/) style, seperated by a custom splitter, which can be set in the config as a regular expression, default 3 or more dashes in an own line.
Example:

```markdown
title: This is the title
subtitle: This is the subtitle
date: 5. September 2013
tags: [code, laravel]
---
# Content goes here

Lorem Ipsum ...
```


#### Usage in Laravel

```php
$file = File::get('test.md');

$document = MarkdownPlus::make($file);
$content = $document->getContent();
$title = $document->title(); // magic method

return View::make('your-view', compact('title', 'content'));
```

The meta data will be parsed with [Symfony's Yaml Parser](https://github.com/symfony/Yaml). After parsing the meta is an multidimensional array.    
! Dates in the format `YYYY-MM-DD` will be parsed into a timestamp.

#### Available methods

`$document->getContent()` - returns the parsed Markdown (HTML-Content)

`$document->getRawContent()` - returns the raw content (pure Markdown)

`$document->getMeta()` - returns the whole meta data as an multidimensional array

`$document->title()` - this is a magic method. The name of the method returns its equivalent meta. For example `$document->cool-meta()` return the value of `cool-meta: Cool Value`

## Configuration

Laravel-Markdown-Plus comes with some basic configuration.    
Publish the configuration to customize the options:

    php artisan config:publish maxxscho/laravel-markdown-plus 

You'll find the config file in `app/config/packages/maxxscho/laravel-markdown-plus`

#### Available Config-Options

`'use_meta' => true,` - whether you wanna use meta data or not

`'section_splitter' => '/\s+-{3,}\s+/',` - the seciton splitter

`'use_extra' => true,` - do you wanna parse markdown with [additional features](https://michelf.ca/projects/php-markdown/extra/)

`'markdown_parser_options'` - options for the markdown parser itself. [More infos](https://michelf.ca/projects/php-markdown/configuration/)

## Licencse

This is free software distributed under the terms of the MIT license.

## Additional information

Inspired by an based on [Dayle Rees Kurenai](https://github.com/daylerees/kurenai).

This package uses:

- [Symfony YAML](https://github.com/symfony/Yaml)
- [Michelf PHP Markdown](https://michelf.ca/projects/php-markdown)