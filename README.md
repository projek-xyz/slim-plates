[![VERSION](https://img.shields.io/packagist/v/projek-xyz/slim-plates.svg?style=flat-square)](https://github.com/projek-xyz/slim-plates/releases)
[![LICENSE](https://img.shields.io/packagist/l/projek-xyz/slim-plates.svg?style=flat-square)](LICENSE.md)
[![Actions Status](https://img.shields.io/github/workflow/status/projek-xyz/slim-plates/Tests/master?style=flat-square&logo=github-actions)](https://github.com/projek-xyz/slim-plates/actions)
[![Coveralls](https://img.shields.io/coveralls/projek-xyz/slim-plates/master.svg?style=flat-square&logo=coveralls)](https://coveralls.io/github/projek-xyz/slim-plates)
[![Code Climate](https://img.shields.io/codeclimate/coverage/projek-xyz/slim-plates.svg?style=flat-square&logo=code-climate)](https://codeclimate.com/github/projek-xyz/slim-plates/coverage)
[![Maintainability](https://img.shields.io/codeclimate/maintainability/projek-xyz/slim-plates?style=flat-square&logo=code-climate)](https://codeclimate.com/github/projek-xyz/slim-plates/maintainability)
[![SensioLabs Insight](https://img.shields.io/sensiolabs/i/0b18f66c-8041-47c3-8004-6eef2b940d30.svg?style=flat-square)](https://insight.sensiolabs.com/projects/0b18f66c-8041-47c3-8004-6eef2b940d30)

# Plates Template Integration for Slim micro framework 3

Render your Slim 3 application views using Plates template engine.

## Install

Via [Composer](https://getcomposer.org/)

```bash
$ composer require projek-xyz/slim-plates --prefer-dist
```

Requires Slim micro framework 3 and PHP 5.5.0 or newer.

## Usage

```php
// Create Slim app
$app = new \Slim\App();

// Fetch DI Container
$container = $app->getContainer();

// Register Plates View helper:
// Option 1, using PlatesProvider
$container->register(new \Projek\Slim\PlatesProvider);

// Option 2, using Closure
$container['view'] = function ($c) {
    $view = new \Projek\Slim\Plates([
        // Path to view directory (default: null)
        'directory' => 'path/to/views',
        // Path to asset directory (default: null)
        'assetPath' => 'path/to/static/assets',
        // Template extension (default: 'php')
        'fileExtension' => 'tpl',
        // Template extension (default: false) see: http://platesphp.com/extensions/asset/
        'timestampInFilename' => false,
    ]);

    // Set \Psr\Http\Message\ResponseInterface object
    // Or you can optionaly pass `$c->get('response')` in `__construct` second parameter
    $view->setResponse($c->get('response'));

    // Instantiate and add Slim specific extension
    $view->loadExtension(new Projek\Slim\PlatesExtension(
        $c->get('router'),
        $c->get('request')->getUri()
    ));

    return $view;
};

// Define named route
$app->get('/hello/{name}', function ($request, $response, $args) {
    return $this->view->render('profile', [
        'name' => $args['name']
    ]);
})->setName('profile');

// Run app
$app->run();
```

**NOTE**:
* If you are using _option 1_ please make sure you already have `$container['settings']['view']` in your configuration file.
* `Plates::setResponse()` is required to use `Plates::render()` otherwise `\LogicException` will thrown.

## Custom functions

This component exposes some Slim functions to your Plates templates.

### `pathFor()`

You can use this function to generate complete URLs to any Slim application named route. Example:

```php
<?php $this->layout('base-template') ?>

<?php $this->start('body') ?>
<h1>Hallo <?=$this->e($name)?></h1>
<small><?=$this->pathFor('profile', ['name'=> $name])?></small>
<?php $this->stop() ?>
```

### `baseUrl($permalink = '')`

Retrieve the base url of your Slim application. Example:

```php
<a href="<?=$this->baseUrl()?>">Some Link</a>
```

Or you can pass a permalink

```php
<a href="<?=$this->baseUrl('some/path')?>">Some Other Link</a>
```

### `basePath()`

Retrieve the base url of your Slim application. Example:

```php
<link rel="stylesheet" href="<?=$this->basePath().'asset/css/main.css'?>">
```

### `uriFull()`

Retrieve full request URI.

### `uriScheme()`

Retrieve the [scheme](https://tools.ietf.org/html/rfc3986#section-3.1) component of the URI.

### `uriHost()`

Retrieve the [host](http://tools.ietf.org/html/rfc3986#section-3.2.2) component of the URI

### `uriPort()`

Retrieve the [port](https://tools.ietf.org/html/rfc3986#section-3.2.3) component of the URI.

### `uriPath()`

Retrieve the [path](https://tools.ietf.org/html/rfc3986#section-3.3) component of the URI.

### `uriQuery()`

Retrieve the [query string](https://tools.ietf.org/html/rfc3986#section-3.4) component of the URI.

### `uriFragment()`

Retrieve the [fragment](https://tools.ietf.org/html/rfc3986#section-3.5) component of the URI.

## Contributing

Please see [CONTRIBUTING](.github/CONTRIBUTING.md) and [CONDUCT](.github/CONDUCT.md) for details.

## License

This library is open-sourced software licensed under [MIT license](LICENSE.md).
