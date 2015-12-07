<?php
namespace Projek\Slim\Tests;

use Slim\Router;
use Slim\Http\Uri;
use Slim\Http\Environment;
use League\Plates\Engine;
use Projek\Slim\PlatesExtension;

class PlatesExtensionTest extends TestCase
{
    public function setUp()
    {
        $this->router = new Router;
        $this->uri = new Uri('http', 'example.com', 80, '/', 'ab=cd');
        $this->view = new Engine(
            __DIR__.'/templates',
            $this->settings['fileExtension']
        );
        $this->view->loadExtension(new PlatesExtension($this->router, $this->uri));
    }

    public function testShouldHavePathforFunc()
    {
        $this->assertTrue($this->view->doesFunctionExist('pathFor'));

        $pathFor = $this->view->getFunction('pathFor')->getCallback();
        $this->router->map(['GET'], '/coba', function ($req, $res) {
            return $res->write('test');
        })->setName('coba');

        $this->assertEquals('/coba', $pathFor('coba'));
    }

    public function testShouldHaveBaseurlFunc()
    {
        $this->assertTrue($this->view->doesFunctionExist('baseUrl'));

        $baseUrl = $this->view->getFunction('baseUrl')->getCallback();
        $this->router->map(['GET'], '/coba', function ($req, $res) {
            return $res->write('test');
        });

        $this->assertEquals('http://example.com/coba', $baseUrl('coba'));
    }

    public function testShouldHaveBasepathFunc()
    {
        $this->assertTrue($this->view->doesFunctionExist('basePath'));

        $basePath = $this->view->getFunction('basePath')->getCallback();
        $this->router->map(['GET'], '/coba', function ($req, $res) {
            return $res->write('test');
        });

        $this->assertEquals('', $basePath());
    }

    public function testShouldHaveSchemeFunc()
    {
        $this->assertTrue($this->view->doesFunctionExist('uriScheme'));

        $uriScheme = $this->view->getFunction('uriScheme')->getCallback();
        $this->router->map(['GET'], '/coba', function ($req, $res) {
            return $res->write('test');
        });

        $this->assertEquals('http', $uriScheme());
    }

    public function testShouldHaveHostFunc()
    {
        $this->assertTrue($this->view->doesFunctionExist('uriHost'));

        $uriHost = $this->view->getFunction('uriHost')->getCallback();
        $this->router->map(['GET'], '/coba', function ($req, $res) {
            return $res->write('test');
        });

        $this->assertEquals('example.com', $uriHost());
    }

    public function testShouldHavePortFunc()
    {
        $this->assertTrue($this->view->doesFunctionExist('uriPort'));

        $uriPort = $this->view->getFunction('uriPort')->getCallback();
        $this->router->map(['GET'], '/coba', function ($req, $res) {
            return $res->write('test');
        });

        $this->assertEquals(null, $uriPort());
    }

    public function testShouldHavePathFunc()
    {
        $this->assertTrue($this->view->doesFunctionExist('uriPath'));

        $uriPath = $this->view->getFunction('uriPath')->getCallback();
        $this->router->map(['GET'], '/coba', function ($req, $res) {
            return $res->write('test');
        });

        $this->assertEquals('/', $uriPath());
    }

    public function testShouldHaveQueryFunc()
    {
        $this->assertTrue($this->view->doesFunctionExist('uriQuery'));

        $uriQuery = $this->view->getFunction('uriQuery')->getCallback();
        $this->router->map(['GET'], '/coba', function ($req, $res) {
            return $res->write('test');
        });

        $this->assertEquals('ab=cd', $uriQuery());
    }
}
