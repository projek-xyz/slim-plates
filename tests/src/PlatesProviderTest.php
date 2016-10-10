<?php
namespace Projek\Slim\Tests;

use League\Plates\Engine;
use Projek\Slim\PlatesProvider;
use Slim\Container;

class PlatesProviderTest extends TestCase
{
    public function testAddContainer()
    {
        $container = new Container([
            'settings' => ['view' => $this->settings]
        ]);
        $container->register(new PlatesProvider);

        $this->assertTrue($container->has('view'));
        $this->assertInstanceOf(Engine::class, $container->get('view')->getPlates());
    }

    /**
     * @expectedException InvalidArgumentException
     */
    public function testInvalidLoggerSettings()
    {
        $container = new Container([
            'settings' => []
        ]);
        $container->register(new PlatesProvider);
    }
}
