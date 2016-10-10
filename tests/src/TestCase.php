<?php
namespace Projek\Slim\Tests;

use PHPUnit_Framework_TestCase;
use Projek\Slim\Plates;

abstract class TestCase extends PHPUnit_Framework_TestCase
{
    /**
     * @var \Projek\Slim\Plates
     */
    protected $view;

    /**
     * Slim Application settings.
     *
     * @var array
     */
    protected $settings = [
        'directory'     => '',
        'assetPath'     => '',
        'fileExtension' => 'tpl',
    ];

    public function setUp()
    {
        $this->settings['directory'] = dirname(__DIR__).'/fixtures';
        $this->view = new Plates($this->settings);
    }
}
