# Slim Framework Plates View

[![Build Status](https://travis-ci.org/feryardiant/slim-plates-view.svg?branch=master)](https://travis-ci.org/feryardiant/slim-plates-view)

This is a Slim Framework view helper built on top of the Plates templating component. You can use this component to create and render templates in your Slim Framework application.

## Install

Via [Composer](https://getcomposer.org/)

```bash
$ composer require slim/plates-view
```

Requires Slim Framework 3 and PHP 5.5.0 or newer.

## Usage

```php
// Create Slim app
$app = new \Slim\App();

// Fetch DI Container
$container = $app->getContainer();

// Register Plates View helper:
// Option 1, using PlatesProvider
$container->register(new \Slim\Views\PlatesProvider);

// Option 2, using Closure
$container['view'] = function ($c) {
    $view = new \Slim\Views\Plates([
        'directory' => 'path/to/views',
        'assetPath' => 'path/to/static/assets',
        'fileExtension' => 'tpl',
    ]);

    // Instantiate and add Slim specific extension
    $view->loadExtension(new Slim\Views\PlatesExtension(
        $c['router'],
        $c['request']->getUri()
    ));

    return $view;
};

// Define named route
$app->get('/hello/{name}', function ($request, $response, $args) {
    return $this->view->render($response, 'profile', [
        'name' => $args['name']
    ]);
})->setName('profile');

// Run app
$app->run();
```

## Custom template functions

This component exposes a custom `$this->pathFor()` function to your Plates templates. You can use this function to generate complete URLs to any Slim application named route. This is an example Plates template:

```html
<?php $this->layout('base-template') ?>

<?php $this->start('body') ?>
<h1>Hallo <?=$this->e($name)?></h1>
<small><?=$this->pathFor('profile', ['name'=> $name])?></small>
<?php $this->stop() ?>
```

## Testing

```bash
phpunit
```

## Contributing

Please see [CONTRIBUTING](CONTRIBUTING.md) for details.

## Credits

- [Fery Wardiyanto](https://github.com/feryardiant)

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.