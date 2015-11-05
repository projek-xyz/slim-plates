<?php
namespace Projek\Slim;

use Pimple\Container;
use Pimple\ServiceProviderInterface;
use InvalidArgumentException;

class PlatesProvider implements ServiceProviderInterface
{
    /**
     * Register this plates view provider with a Pimple container
     *
     * @param Container $container
     */
    public function register(Container $container)
    {
        if (!$container['settings']->has('view')) {
            throw new InvalidArgumentException('Template configuration not found');
        }

        $engine = new Plates($container['settings']->get('view'));

        $engine->loadExtension(
            new PlatesExtension($container['router'], $container['request']->getUri())
        );

        $container['view'] = $engine;
    }
}