<?php

use League\Plates\Engine;
use Projek\Slim\Plates;
use Psr\Http\Message\ResponseInterface;
use Slim\Psr7\Factory\{ResponseFactory, StreamFactory};

use function Kahlan\{beforeEach, describe, expect, it};

describe(Plates::class, function () {
    beforeEach(function () {
        $this->view = function (array $config = []) {
            $config = array_merge([
                'directory'     => $this->stubPath(),
                'assetPath'     => '',
                'fileExtension' => 'tpl',
            ], $config);

            return new Plates($config, new StreamFactory);
        };
    });

    it('Should return plates engine', function () {
        $view = $this->view();

        expect($view->getPlates())->toBeAnInstanceOf(Engine::class);
        expect($view->addFolder('foo', $this->stubPath()))->toBeAnInstanceOf(Engine::class);
        expect($view->addData(['foo' => 'bar']))->toBeAnInstanceOf(Engine::class);
        expect($view->registerFunction('foobar', function () {
            return true;
        }))->toBeAnInstanceOf(Engine::class);
    });

    it('Should render', function () {
        $view = $this->view();
        $response = (new ResponseFactory)->createResponse();

        expect(
            $view->render($response, 'example', ['name' => 'Fery'])
        )->toBeAnInstanceOf(ResponseInterface::class);
    });
});
