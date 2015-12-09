<?php
namespace Projek\Slim;

use League\Plates\Engine;
use League\Plates\Extension\Asset;
use League\Plates\Extension\ExtensionInterface;
use Psr\Http\Message\ResponseInterface;

class Plates
{
    /**
     * @var string[]
     */
    private $settings = [
        'directory' => null,
        'assetPath' => null,
        'fileExtension' => 'php',
        'timestampInFilename' => false,
    ];

    /**
     * @var \League\Plates\Engine
     */
    private $plates;

    public function __construct($settings)
    {
        $this->settings = array_merge($this->settings, $settings);
        $this->plates = new Engine($this->settings['directory'], $this->settings['fileExtension']);

        if (null !== $this->settings['assetPath']) {
            $this->setAssetPath($this->settings['assetPath']);
        }
    }

    /**
     * Get the Plate Engine
     *
     * @return \League\Plates\Engine
     */
    public function getPlates()
    {
        return $this->plates;
    }

    /**
     * Set Asset path from Plates Asset Extension
     *
     * @param string $assetPath
     */
    public function setAssetPath($assetPath)
    {
        return $this->plates->loadExtension(new Asset($assetPath, $this->settings['timestampInFilename']));
    }

    /**
     * Set Asset path from Plates Asset Extension
     *
     * @param  \Psr\Http\Message\ResponseInterface $extension
     * @return \League\Plates\Engine
     */
    public function loadExtension(ExtensionInterface $extension)
    {
        $extension->register($this->plates);

        return $this->plates;
    }

    /**
     * Add a new template folder for grouping templates under different namespaces.
     *
     * @param  string  $name
     * @param  string  $directory
     * @param  boolean $fallback
     * @return \League\Plates\Engine
     */
    public function addFolder($name, $directory, $fallback = false)
    {
        return $this->plates->addFolder($name, $directory, $fallback);
    }

    /**
     * Add preassigned template data.
     *
     * @param  array         $data
     * @param  null|string[] $templates
     * @return \League\Plates\Engine
     */
    public function addData(array $data, $templates = null)
    {
        return $this->plates->addData($data, $templates);
    }

    /**
     * Register a new template function.
     *
     * @param  string   $name
     * @param  callable $callback
     * @return \League\Plates\Engine
     */
    public function registerFunction($name, $callback)
    {
        return $this->plates->registerFunction($name, $callback);
    }

    /**
     * Render the template
     *
     * @param  \Psr\Http\Message\ResponseInterface $response
     * @param  string                              $name
     * @param  string[]                            $data
     * @return \Psr\Http\Message\ResponseInterface
     */
    public function render(ResponseInterface $response, $name, array $data = [])
    {
        return $response->getBody()->write($this->plates->render($name, $data));
    }
}
