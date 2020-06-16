<?php

use League\Plates\Engine;
use Projek\Slim\Plates;
use Psr\Http\Message\ResponseInterface;
use Slim\Psr7\Factory\ResponseFactory;
use Slim\Psr7\Factory\StreamFactory;

use function Kahlan\beforeEach;
use function Kahlan\describe;
use function Kahlan\given;

describe(Plates::class, function () {
    beforeEach(function () {
        $this->directory = dirname(__DIR__).'/stub';
        $this->view = new Plates([
            'directory'     => $this->directory,
            'assetPath'     => '',
            'fileExtension' => 'tpl',
        ], new StreamFactory);
    });

    it('Should return plates engine', function () {
        expect($this->view->getPlates())->toBeAnInstanceOf(Engine::class);
        expect($this->view->addFolder('foo', $this->directory))->toBeAnInstanceOf(Engine::class);
        expect($this->view->addData(['foo' => 'bar']))->toBeAnInstanceOf(Engine::class);
        expect($this->view->registerFunction('foobar', function () {
            return true;
        }))->toBeAnInstanceOf(Engine::class);
    });

    it('Should render', function () {
        $response = (new ResponseFactory)->createResponse();

        expect(
            $this->view->render($response, 'example', ['name' => 'Fery'])
        )->toBeAnInstanceOf(ResponseInterface::class);
    });
});
