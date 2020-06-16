<?php
namespace Projek\Slim;

use League\Plates\Engine;
use League\Plates\Extension\ExtensionInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Message\UriInterface;
use Slim\Interfaces\RouteInterface;
use Slim\Routing\RouteContext;

class PlatesExtension implements ExtensionInterface
{
    /**
     * @var RouteContext
     */
    private $context;

    /**
     * @var UriInterface
     */
    private $uri;

    /**
     * Create new Asset instance.
     *
     * @param ServerRequestInterface $request
     */
    public function __construct(ServerRequestInterface $request)
    {
        $this->context = RouteContext::fromRequest($request);
        $this->uri = $request->getUri();
    }

    /**
     * Register extension function.
     *
     * @param Engine $engine Plates instance
     */
    public function register(Engine $engine) : void
    {
        $engine->registerFunction('baseUrl', [$this, 'baseUrl']);
        $engine->registerFunction('uriFull', [$this, 'uriFull']);

        $engine->registerFunction('route', [$this->context, 'getRoute']);
        $engine->registerFunction('basePath', [$this->context, 'getBasePath']);

        $engine->registerFunction('urlFor', [$this->context->getRouteParser(), 'urlFor']);
        $engine->registerFunction('fullUrlFor', [$this->context->getRouteParser(), 'fullUrlFor']);
        $engine->registerFunction('relativeUrlFor', [$this->context->getRouteParser(), 'relativeUrlFor']);

        $engine->registerFunction('uriScheme', [$this->uri, 'getScheme']);
        $engine->registerFunction('uriHost', [$this->uri, 'getHost']);
        $engine->registerFunction('uriPort', [$this->uri, 'getPort']);
        $engine->registerFunction('uriPath', [$this->uri, 'getPath']);
        $engine->registerFunction('uriQuery', [$this->uri, 'getQuery']);
        $engine->registerFunction('uriFragment', [$this->uri, 'getFragment']);
    }

    /**
     * Retrieve slim baseUrl.
     *
     * @param string $permalink You can add optional permalink
     *
     * @return string
     */
    public function baseUrl($permalink = '') : string
    {
        return $this->context->getBasePath().'/'.ltrim($permalink, '/');
    }

    /**
     * Retrieve slim uri (string).
     *
     * @return string
     */
    public function uriFull() : string
    {
        return (string) $this->uri;
    }

    /**
     * Retrieve slim uri (string).
     *
     * @return RouteInterface|null
     */
    public function getRoute() : ?RouteInterface
    {
        return $this->context->getRoute();
    }
}
