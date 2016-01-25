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
        if (!isset($container->get('settings')['view'])) {
            throw new InvalidArgumentException('Template configuration not found');
        }

        $engine = new Plates(
            $container->get('settings')['view'],
            $container->get('response')
        );

        $engine->loadExtension(
            new PlatesExtension(
                $container->get('router'),
                $container->get('request')->getUri()
            )
        );

        $container['view'] = $engine;
    }
}
