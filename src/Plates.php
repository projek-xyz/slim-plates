<?php
namespace Projek\Slim;

use League\Plates\Engine;
use League\Plates\Extension\Asset;
use League\Plates\Extension\ExtensionInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\StreamFactoryInterface;

class Plates
{
    /**
     * Default settings.
     *
     * @var array<string, mixed>
     */
    private $settings = [
        'directory' => null,
        'assetPath' => null,
        'fileExtension' => 'php',
        'timestampInFilename' => false,
    ];

    /**
     * Plates Engine instance.
     *
     * @var Engine
     */
    private $plates;

    /**
     * @var StreamFactoryInterface
     */
    private $streamFactory;

    /**
     * Create new Projek\Slim\Plates instance.
     *
     * @param array<string, mixed>    $settings
     * @param StreamFactoryInterface  $streamFactory
     */
    public function __construct(array $settings, StreamFactoryInterface $streamFactory)
    {
        $this->settings = array_merge($this->settings, $settings);
        $this->plates = new Engine($this->settings['directory'], $this->settings['fileExtension']);
        $this->streamFactory = $streamFactory;

        if (null !== $this->settings['assetPath']) {
            $this->setAssetPath($this->settings['assetPath']);
        }
    }

    /**
     * Get the Plate Engine.
     *
     * @return Engine
     */
    public function getPlates()
    {
        return $this->plates;
    }

    /**
     * Set Asset path from Plates Asset Extension.
     *
     * @param string $assetPath
     *
     * @return Engine
     */
    public function setAssetPath($assetPath)
    {
        return $this->plates->loadExtension(
            new Asset($assetPath, $this->settings['timestampInFilename'])
        );
    }

    /**
     * Set Asset path from Plates Asset Extension.
     *
     * @param ExtensionInterface $extension
     *
     * @return Engine
     */
    public function loadExtension(ExtensionInterface $extension)
    {
        $extension->register($this->plates);

        return $this->plates;
    }

    /**
     * Add a new template folder for grouping templates under different namespaces.
     *
     * @param string $name
     * @param string $directory
     * @param bool   $fallback
     *
     * @throws \LogicException
     *
     * @return Engine
     */
    public function addFolder($name, $directory, $fallback = false)
    {
        return $this->plates->addFolder($name, $directory, $fallback);
    }

    /**
     * Add preassigned template data.
     *
     * @param array         $data
     * @param null|string[] $templates
     *
     * @throws \LogicException
     *
     * @return Engine
     */
    public function addData(array $data, $templates = null)
    {
        return $this->plates->addData($data, $templates);
    }

    /**
     * Register a new template function.
     *
     * @param string   $name
     * @param callable $callback
     *
     * @throws \LogicException
     *
     * @return Engine
     */
    public function registerFunction($name, $callback)
    {
        return $this->plates->registerFunction($name, $callback);
    }

    /**
     * Render the template.
     *
     * @param ResponseInterface     $response
     * @param string                $name
     * @param array<string, mixed>  $data
     *
     * @throws \LogicException
     *
     * @return StreamFactoryInterface
     */
    public function render(ResponseInterface $response, $name, array $data = [])
    {
        $rendered = $this->streamFactory->createStream(
            $this->plates->render($name, $data)
        );

        return $response->withBody($rendered);
    }
}
