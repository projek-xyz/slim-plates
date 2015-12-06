<?php
namespace Projek\Slim\Tests;

use Projek\Slim\Plates;
use League\Plates\Engine;

class PlatesTest extends TestCase
{
    public function testShoudReturnsPlatesEngine()
    {
        $this->assertInstanceOf(Engine::class, $this->view->getPlates());
        $this->assertInstanceOf(Engine::class, $this->view->addFolder('foo', __DIR__.'/templates'));
        $this->assertInstanceOf(Engine::class, $this->view->addData(['foo' => 'bar']));
        $this->assertInstanceOf(
            Engine::class,
            $this->view->registerFunction('foobar', function () {
                return true;
            })
        );
    }

    public function testRender()
    {
        $mockBody = $this->getMockBuilder('Psr\Http\Message\StreamInterface')
            ->disableOriginalConstructor()
            ->getMock();

        $mockResponse = $this->getMockBuilder('Psr\Http\Message\ResponseInterface')
            ->disableOriginalConstructor()
            ->getMock();

        $mockBody->expects($this->once())
            ->method('write')
            ->with("<p>Hi, my name is Fery.</p>\n")
            ->willReturn($mockResponse);

        $mockResponse->expects($this->once())
            ->method('getBody')
            ->willReturn($mockBody);

        $response = $this->view->render($mockResponse, 'example', [
            'name' => 'Fery'
        ]);

        $this->assertInstanceOf('Psr\Http\Message\ResponseInterface', $response);
    }
}
