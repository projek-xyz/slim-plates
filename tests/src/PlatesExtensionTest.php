<?php
namespace Projek\Slim\Tests;

use League\Plates\Engine;
use Projek\Slim\PlatesExtension;
use Slim\Http\Uri;
use Slim\Router;

class PlatesExtensionTest extends TestCase
{
    protected function getFuncCallback($name)
    {
        $engine = new Engine(
            $this->settings['directory'],
            $this->settings['fileExtension']
        );

        $engine->loadExtension(new PlatesExtension(
            $router = new Router,
            new Uri('http', 'example.com', 80, '/', 'ab=cd', 'top')
        ));

        $this->assertTrue($engine->doesFunctionExist($name));

        $callback = $engine->getFunction($name)->getCallback();

        $this->assertTrue(is_callable($callback));

        $router->map(['GET'], '/coba', function ($req, $res) {
            return $res->write('test');
        })->setName('coba');

        return $callback;
    }

    public function testShouldHavePathforFunc()
    {
        $callback = $this->getFuncCallback('pathFor');

        $this->assertEquals('/coba', $callback('coba'));
    }

    public function testShouldHaveBaseurlFunc()
    {
        $callback = $this->getFuncCallback('baseUrl');

        $this->assertEquals('http://example.com/coba', $callback('coba'));
    }

    public function testShouldHaveBasepathFunc()
    {
        $callback = $this->getFuncCallback('basePath');

        $this->assertEquals('', $callback());
    }

    public function testShouldHaveUriFunc()
    {
        $callback = $this->getFuncCallback('uriFull');

        $this->assertEquals('http://example.com/?ab=cd#top', $callback());
    }

    public function testShouldHaveSchemeFunc()
    {
        $callback = $this->getFuncCallback('uriScheme');

        $this->assertEquals('http', $callback());
    }

    public function testShouldHaveHostFunc()
    {
        $callback = $this->getFuncCallback('uriHost');

        $this->assertEquals('example.com', $callback());
    }

    public function testShouldHavePortFunc()
    {
        $callback = $this->getFuncCallback('uriPort');

        $this->assertEquals(null, $callback());
    }

    public function testShouldHavePathFunc()
    {
        $callback = $this->getFuncCallback('uriPath');

        $this->assertEquals('/', $callback());
    }

    public function testShouldHaveQueryFunc()
    {
        $callback = $this->getFuncCallback('uriQuery');

        $this->assertEquals('ab=cd', $callback());
    }

    public function testShouldHaveFragmentFunc()
    {
        $callback = $this->getFuncCallback('uriFragment');

        $this->assertEquals('top', $callback());
    }
}
