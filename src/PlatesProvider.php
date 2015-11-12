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
        if (!isset($container->get('settings')['view'])) {
            throw new InvalidArgumentException('Template configuration not found');
        }

        $engine = new Plates($container->get('settings')['view']);

        $engine->loadExtension(
            new PlatesExtension(
                $container->get('router'),
                $container->get('request')->getUri()
            )
        );

        $container['view'] = $engine;
    }
}
