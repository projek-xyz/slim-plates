<?php
namespace Projek\Slim;

use InvalidArgumentException;
use Pimple\Container;
use Pimple\ServiceProviderInterface;

class PlatesProvider implements ServiceProviderInterface
{
    /**
     * Register this plates view provider with a Pimple container.
     *
     * @param \Pimple\Container $container
     */
    public function register(Container $container)
    {
        if (!isset($container['settings']['view'])) {
            throw new InvalidArgumentException('Template configuration not found');
        }

        $engine = new Plates(
            $container['settings']['view'],
            $container['response']
        );

        $engine->loadExtension(
            new PlatesExtension(
                $container['router'],
                $container['request']->getUri()
            )
        );

        $container['view'] = $engine;
    }
}
