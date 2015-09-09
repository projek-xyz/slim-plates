<?php
namespace ProjekSlim\Views;

use League\Plates\Extension\Asset;
use League\Plates\Extension\URI;
use Pimple\Container;
use Pimple\ServiceProviderInterface;
use InvalidArgumentException;

class PlatesProvider implements ServiceProviderInterface
{
    /**
     * Register this plates view provider with a Pimple container
     *
     * @param  Container $container
     */
    public function register(Container $container)
    {
        if (!isset($container['settings']['plates'])) {
            throw new InvalidArgumentException('Template configuration not found');
        }

        $engine = new Plates($container['settings']['plates']);

        if (isset($container['request'])) {
            $uri = $container['request']->getUri();
            $engine->loadExtension(new URI($uri->getBaseUrl()));
            $engine->loadExtension(new PlatesExtension($container['router'], $uri));
        }

        if (isset($container['settings']['assetPath'])) {
            $engine->loadExtension(new Asset($container['settings']['assetPath']));
        }

        $container['view'] = $engine;
    }
}