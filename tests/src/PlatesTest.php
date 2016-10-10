<?php
namespace Projek\Slim\Tests;

use League\Plates\Engine;
use Psr\Http\Message\ResponseInterface;
use Slim\Http\Response;

class PlatesTest extends TestCase
{
    public function testShoudReturnsPlatesEngine()
    {
        $this->assertInstanceOf(Engine::class, $this->view->getPlates());
        $this->assertInstanceOf(Engine::class, $this->view->addFolder('foo', $this->settings['directory']));
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

        $this->assertInstanceOf(
            ResponseInterface::class,
            $this->view->render('example', ['name' => 'Fery'])
        );
    }

    /**
     * @expectedException \LogicException
     */
    public function testInvalidResponseObj()
    {
        $this->view->render('example', ['name' => 'Fery']);
    }
}
