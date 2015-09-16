<?php
namespace Projek\Slim;

use League\Plates\Engine;
use League\Plates\Extension\ExtensionInterface;
use Psr\Http\Message\ResponseInterface;
use InvalidArgumentException;

class Plates
{
    /**
     * @var array
     */
    private $settings = [
        'directory' => null,
        'assetPath' => null,
        'fileExtension' => 'php',
    ];

    /**
     * @var  League\Plates\Engine
     */
    private $engine;

    /**
     * Register this plates view provider with a Pimple container
     *
     * @param  Container $container
     */
    public function __construct($settings)
    {
        $settings = array_merge($this->settings, $settings);
        $engine = new Engine($settings['directory'], $settings['fileExtension']);

        $this->engine = $engine;
    }

    /**
     * Get the Plate Engine
     *
     * @return  League\Plates\Engine
     */
    public function getEngine()
    {
        return $this->engine;
    }

    /**
     * Set Asset path from Plates Asset Extension
     *
     * @param  string  $assetPath  Asset path
     */
    public function setAssetPath($assetPath)
    {
        return $this->engine->loadExtension(new Asset($assetPath));
    }

    /**
     * Set Asset path from Plates Asset Extension
     *
     * @param   Psr\Http\Message\ResponseInterface  $extension
     * @return  League\Plates\Engine
     */
    public function loadExtension(ExtensionInterface $extension)
    {
        $extension->register($this->engine);

        return $this;
    }

    /**
     * Add a new template folder for grouping templates under different namespaces.
     *
     * @param   string   $name
     * @param   string   $directory
     * @param   boolean  $fallback
     * @return  League\Plates\Engine
     */
    public function addFolder($name, $directory, $fallback = false)
    {
        return $this->engine->addFolder($name, $directory, $fallback);
    }

    /**
     * Add preassigned template data.
     *
     * @param   array              $data;
     * @param   null|string|array  $templates;
     * @return  League\Plates\Engine
     */
    public function addData(array $data, $templates = null)
    {
        return $this->engine->addData($data, $templates);
    }

    /**
     * Register a new template function.
     *
     * @param   string    $name;
     * @param   callback  $callback;
     * @return  League\Plates\Engine
     */
    public function registerFunction($name, $callback)
    {
        return $this->engine->registerFunction($name, $callback);
    }

    /**
     * Render the template
     *
     * @param   string  $name  Template name
     * @param   array   $data  Template data
     * @return  Psr\Http\Message\ResponseInterface
     */
    public function render(ResponseInterface $response, $name, array $data = [])
    {
        return $response->getBody()->write($this->engine->render($name, $data));
    }
}