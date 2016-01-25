<?php
namespace Projek\Slim\Tests;

use Projek\Slim\Plates;
use League\Plates\Engine;
use Psr\Http\Message\ResponseInterface;
use Slim\Http\Response;

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
        $mockResponse = $this->getMockBuilder(Response::class)
            ->disableOriginalConstructor()
            ->getMock();

        $mockResponse->expects($this->once())
            ->method('write')
            ->with("<p>Hi, my name is Fery.</p>\n")
            ->willReturn($mockResponse);

        $this->view->setResponse($mockResponse);

        $response = $this->view->render('example', [
            'name' => 'Fery'
        ]);

        $this->assertInstanceOf(ResponseInterface::class, $response);
    }

    /**
     * @expectedException \LogicException
     */
    public function testInvalidResponseObj()
    {
        $response = $this->view->render('example', [
            'name' => 'Fery'
        ]);
    }
}
