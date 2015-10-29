<?php
namespace Projek\Slim;

use League\Plates\Engine;
use League\Plates\Extension\ExtensionInterface;
use Psr\Http\Message\UriInterface;
use Slim\Interfaces\RouterInterface;

class PlatesExtension implements ExtensionInterface
{
    /**
     * @var Slim\Interfaces\RouterInterface
     */
    private $router;

    /**
     * @var string|Slim\Http\Uri
     */
    private $uri;

    /**
     * Create new Asset instance.
     *
     * @param string  $path
     * @param boolean $filenameMethod
     */
    public function __construct(RouterInterface $router, UriInterface $uri)
    {
        $this->router = $router;
        $this->uri = $uri;
    }

    /**
     * Register extension function.
     * @return null
     */
    public function register(Engine $engine)
    {
        $engine->registerFunction('pathFor',   [$this, 'pathFor']);
        $engine->registerFunction('baseUrl',   [$this, 'baseUrl']);
        $engine->registerFunction('basePath',  [$this, 'basePath']);
        $engine->registerFunction('uriScheme', [$this, 'scheme']);
        $engine->registerFunction('uriPath',   [$this, 'path']);
        $engine->registerFunction('uriHost',   [$this, 'host']);
        $engine->registerFunction('uriPort',   [$this, 'port']);
        $engine->registerFunction('uriQuery',  [$this, 'query']);
    }

    /**
     * Retrieve Slim Router::pathFor()
     *
     * @param  string $name        Router name
     * @param  array  $data        Optional data you wanna add to
     * @param  array  $queryParams Optional query parameter
     * @param  string $appName     Optional application name
     * @return string
     */
    public function pathFor($name, $data = [], $queryParams = [], $appName = 'default')
    {
        return $this->router->pathFor($name, $data, $queryParams);
    }

    /**
     * Retrieve slim baseUrl
     *
     * @param  string $permalink You can add optional permalink
     * @return string
     */
    public function baseUrl($permalink = '')
    {
        $uri = $this->uri->getBaseUrl();
        return rtrim($url, '/').'/'.$permalink;
    }

    /**
     * Retrieve the base path segment of the URI.
     *
     * @return string
     */
    public function basePath() {
        return $this->uri->getBasePath();
    }

    /**
     * Retrieve the path component of the URI.
     *
     * @return string
     */
    public function path() {
        return $this->uri->getPath();
    }

    /**
     * Retrieve the scheme component of the URI.
     *
     * @return string
     */
    public function scheme() {
        return $this->uri->getScheme();
    }

    /**
     * Retrieve the host component of the URI.
     *
     * @return string
     */
    public function host() {
        return $this->uri->getHost();
    }

    /**
     * Retrieve the port component of the URI.
     *
     * @return string
     */
    public function port() {
        return $this->uri->getPort();
    }

    /**
     * Retrieve the query string of the URI.
     *
     * @return string
     */
    public function query() {
        return $this->uri->getQuery();
    }
}