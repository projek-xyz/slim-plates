<?php
namespace Projek\Slim\Tests;

use League\Plates\Engine;
use Projek\Slim\PlatesProvider;
use Slim\App;

class PlatesProviderTest extends TestCase
{
    public function testAddContainer()
    {
        $app = new App([
            'settings' => [
                'view' => $this->settings
            ]
        ]);
        $container = $app->getContainer();
        $container->register(new PlatesProvider);

        $this->assertTrue($container->has('view'));
        $this->assertInstanceOf(Engine::class, $container->get('view')->getPlates());
    }

    /**
     * @expectedException InvalidArgumentException
     */
    public function testInvalidLoggerSettings()
    {
        unset($this->settings['view']);
        $app = new App(['settings' => $this->settings]);
        $container = $app->getContainer();
        $container->register(new PlatesProvider);
    }
}
