<?php
namespace Projek\Slim\Tests;

use Projek\Slim\Plates;
use PHPUnit_Framework_TestCase;

require dirname(__DIR__) . '/vendor/autoload.php';

class PlatesTest extends PHPUnit_Framework_TestCase
{
    /**
     * @var Slim\Views\Plates
     */
    protected $view;

    public function setUp()
    {
        $this->view = new Plates([
            'directory' => __DIR__ . '/templates',
            'fileExtension' => 'tpl'
        ]);
    }

    public function testShoudReturnsPlatesEngine()
    {
        $this->assertInstanceOf('League\Plates\Engine', $this->view->getEngine());
        $this->assertInstanceOf('League\Plates\Engine', $this->view->addFolder('foo', __DIR__.'/templates'));
        $this->assertInstanceOf('League\Plates\Engine', $this->view->addData(['foo' => 'bar']));
        $this->assertInstanceOf(
            'League\Plates\Engine',
            $this->view->registerFunction('foobar', function () {
                return true;
            })
        );
    }

    // public function testGetEngine()
    // {
    //     //
    // }

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
