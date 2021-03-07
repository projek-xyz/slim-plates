<?php

use Projek\Slim\PlatesExtension;
use Slim\CallableResolver;
use Slim\Psr7\Factory\{ResponseFactory, ServerRequestFactory, UriFactory};
use Slim\Routing\{Dispatcher, RouteCollector, RouteContext, RouteParser, RoutingResults};

use function Kahlan\{beforeEach, describe, expect, it};

describe(PlatesExtension::class, function () {
    beforeEach(function () {
        $view = $this->view();
        $basePath = 'http://example.com';
        $routes = new RouteCollector(
            new ResponseFactory(),
            new CallableResolver()
        );

        $routes->map(['GET'], '/coba', function ($req, $res) {
            return $res;
        })->setName('coba');

        $this->uri = (new UriFactory)->createUri('http://example.com/coba?foo=bar#baz');
        $results = new RoutingResults(new Dispatcher($routes), 'GET', $basePath, RoutingResults::FOUND);
        $request = (new ServerRequestFactory())->createServerRequest($results->getMethod(), $this->uri)
            ->withAttribute(RouteContext::ROUTING_RESULTS, $results)
            ->withAttribute(RouteContext::ROUTE_PARSER, new RouteParser($routes))
            ->withAttribute(RouteContext::BASE_PATH, $basePath);

        $view->loadExtension(new PlatesExtension($request));

        $this->getFunc = function (string $name, ...$params) use ($view) {
            /** @var \League\Plates\Engine $engine */
            $engine = $view->getPlates();

            expect($engine->doesFunctionExist($name))->toBeTruthy();

            return call_user_func_array($engine->getFunction($name)->getCallback(), $params);
        };
    });

    it('Should have baseUrl function', function () {
        expect(
            $this->getFunc('baseUrl', 'coba')
        )->toEqual('http://example.com/coba');
    });

    it('Should have uriFull function', function () {
        expect(
            $this->getFunc('uriFull', 'coba')
        )->toEqual('http://example.com/coba?foo=bar#baz');
    });

    it('Should have basePath function', function () {
        expect(
            $this->getFunc('basePath')
        )->toEqual('http://example.com');
    });

    it('Should have urlFor function', function () {
        expect(
            $this->getFunc('urlFor', 'coba')
        )->toEqual('/coba');
    });

    it('Should have fullUrlFor function', function () {
        expect(
            $this->getFunc('fullUrlFor', $this->uri, 'coba')
        )->toEqual('http://example.com/coba');
    });

    it('Should have relativeUrlFor function', function () {
        expect(
            $this->getFunc('relativeUrlFor', 'coba')
        )->toEqual('/coba');
    });

    it('Should have uriScheme function', function () {
        expect(
            $this->getFunc('uriScheme')
        )->toEqual('http');
    });

    it('Should have uriHost function', function () {
        expect(
            $this->getFunc('uriHost')
        )->toEqual('example.com');
    });

    it('Should have uriPort function', function () {
        expect(
            $this->getFunc('uriPort')
        )->toBeNull();
    });

    it('Should have uriPath function', function () {
        expect(
            $this->getFunc('uriPath')
        )->toEqual('/coba');
    });

    it('Should have uriQuery function', function () {
        expect(
            $this->getFunc('uriQuery')
        )->toEqual('foo=bar');
    });

    it('Should have uriFragment function', function () {
        expect(
            $this->getFunc('uriFragment')
        )->toEqual('baz');
    });
});
