<?php

use Kahlan\Plugin\Double;
use League\Plates\Engine;
use Projek\Slim\Plates;
use Projek\Slim\PlatesExtension;
use Slim\Psr7\Factory\ServerRequestFactory;
use Slim\Psr7\Factory\StreamFactory;
use Slim\Routing\RouteContext;
use Slim\Routing\RouteParser;
use Slim\Routing\RoutingResults;

use function Kahlan\describe;
use function Kahlan\skipIf;

describe(PlatesExtension::class, function () {
    skipIf(true);

    beforeEach(function () {

        $this->view = new Plates([
            'directory'     => dirname(__DIR__).'/stub',
            'assetPath'     => '',
            'fileExtension' => 'tpl',
        ], new StreamFactory);

        $this->request = (new ServerRequestFactory())
            ->createServerRequest('GET', 'http://example.com')
            ->withAttribute(RouteContext::ROUTING_RESULTS, Double::instance(['extends' => [RoutingResults::class]]))
            ->withAttribute(RouteContext::ROUTE_PARSER, Double::instance(['extends' => [RouteParser::class]]));

        $this->view->loadExtension(new PlatesExtension($this->request));

        $this->getFunction = function (string $name) {
            /** @var Engine $engine */
            $engine = $this->view->getPlates();

            expect($engine->doesFunctionExist($name))->toBeTruthy();

            return $engine->getFunction($name);
        };
    });

    it('Should have baseUrl function', function () {
        $func = $this->getFunction('baseUrl');

        expect($func)->toThrow(Throwable::class);
    });

    it('Should have uriFull function', function () {
        $func = $this->getFunction('uriFull');

        expect($func)->toThrow(Throwable::class);
    });

    it('Should have route function', function () {
        $func = $this->getFunction('route');

        expect($func)->toThrow(Throwable::class);
    });

    it('Should have basePath function', function () {
        $func = $this->getFunction('basePath');

        expect($func)->toThrow(Throwable::class);
    });

    it('Should have urlFor function', function () {
        $func = $this->getFunction('urlFor');

        expect($func)->toThrow(Throwable::class);
    });

    it('Should have fullUrlFor function', function () {
        $func = $this->getFunction('fullUrlFor');

        expect($func)->toThrow(Throwable::class);
    });

    it('Should have relativeUrlFor function', function () {
        $func = $this->getFunction('relativeUrlFor');

        expect($func)->toThrow(Throwable::class);
    });

    it('Should have uriScheme function', function () {
        $func = $this->getFunction('uriScheme');

        expect($func)->toThrow(Throwable::class);
    });

    it('Should have uriHost function', function () {
        $func = $this->getFunction('uriHost');

        expect($func)->toThrow(Throwable::class);
    });

    it('Should have uriPort function', function () {
        $func = $this->getFunction('uriPort');

        expect($func)->toThrow(Throwable::class);
    });

    it('Should have uriPath function', function () {
        $func = $this->getFunction('uriPath');

        expect($func)->toThrow(Throwable::class);
    });

    it('Should have uriQuery function', function () {
        $func = $this->getFunction('uriQuery');

        expect($func)->toThrow(Throwable::class);
    });

    it('Should have uriFragment function', function () {
        $func = $this->getFunction('uriFragment');

        expect($func)->toThrow(Throwable::class);
    });
});
