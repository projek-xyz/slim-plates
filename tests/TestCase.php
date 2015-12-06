<?php
namespace Projek\Slim\Tests;

use Projek\Slim\Plates;
use PHPUnit_Framework_TestCase;
use DateTimeZone;

abstract class TestCase extends PHPUnit_Framework_TestCase
{
    /**
     * @var \Projek\Slim\Plates
     */
    protected $view;

    /**
     * Slim Application settings
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
        $this->settings['directory'] = __DIR__.'/templates';
        $this->view = new Plates($this->settings);
    }
}
