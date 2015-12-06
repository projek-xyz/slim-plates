<?php
namespace Projek\Slim\Tests;

use Slim\App;
use League\Plates\Engine;
use Projek\Slim\PlatesExtension;

class PlatesExtensionTest extends TestCase
{
    /**
     * Slim App Instance
     *
     * @var \Slim\App
     */
    private $app;

    public function setUp()
    {
        // It's not a valid test, right? lol.
        $this->settings['directory'] = __DIR__.'/templates';
        $this->app = new App([
            'settings' => ['view' => $this->settings]
        ]);

        $settings = $this->app->getContainer()->get('settings')['view'];
        $this->view = new Engine($settings['directory'], $settings['fileExtension']);
        $this->view->loadExtension(
            new PlatesExtension(
                $this->app->getContainer()->get('router'),
                $this->app->getContainer()->get('request')->getUri()
            )
        );
    }

    public function testShouldHavePathforFunc()
    {
        $this->assertTrue($this->view->doesFunctionExist('pathFor'));
    }
}
