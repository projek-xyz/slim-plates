<?php
namespace Slim\Views;

use League\Plates\Engine;
use League\Plates\Extension\ExtensionInterface;

class PlatesExtension implements ExtensionInterface
{
    /**
     * @var \Slim\Interfaces\RouterInterface
     */
    private $router;

    /**
     * @var string|\Slim\Http\Uri
     */
    private $uri;

    /**
     * Create new Asset instance.
     *
     * @param string  $path
     * @param boolean $filenameMethod
     */
    public function __construct($router, $uri)
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
        $engine->registerFunction('pathFor', [$this, 'pathFor']);
        $engine->registerFunction('baseUrl', [$this, 'baseUrl']);
    }

    public function pathFor($name, $data = [], $queryParams = [], $appName = 'default')
    {
        return $this->router->pathFor($name, $data, $queryParams);
    }

    public function baseUrl()
    {
        $uri = null;
        if (is_string($this->uri)) {
            $uri = $this->uri;
        } elseif (method_exists($this->uri, 'getBaseUrl')) {
            $uri = $this->uri->getBaseUrl();
        }
    }
}