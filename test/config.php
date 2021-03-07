<?php
/**
 * @link https://kahlan.github.io/docs/config-file.html
 */

use Kahlan\Filter\Filters;
use Kahlan\Reporter\Coverage\Exporter;
use Projek\Slim\Plates;
use Slim\Psr7\Factory\StreamFactory;

/** @var Kahlan\Cli\CommandLine $cli */
$cli = $this->commandLine();
$cli->option('coverage', 'default', 3);
$cli->option('spec', 'default', ['test/spec']);
$cli->option('lcov', 'default', 'test/lcov.info');

Filters::apply($this, 'reporting', function ($next) {
    if (! $reporter = $this->reporters()->get('coverage')) {
        return;
    }

    $lcov_file = getenv('LCOV_REPORT') ?: $this->reporters()->get('lcov');

    if ($lcov_file) {
        Exporter\Lcov::write([
            'collector' => $reporter,
            'file'      => $lcov_file,
        ]);
    }

    return $next();
});

Filters::apply($this, 'run', function($next) {
    /** @var \Kahlan\Block\Group $group */
    $group = $this->suite()->root();
    /** @var \Kahlan\Scope $scope */
    $scope = $group->scope(); // The top most describe scope.

    $scope->given('stubPath', function () {
        return __DIR__ . DIRECTORY_SEPARATOR . 'stub';
    });

    $scope->view = function (array $config = []) {
        $config = array_merge([
            'directory'     => $this->stubPath,
            'assetPath'     => '',
            'fileExtension' => 'tpl',
        ], $config);

        return new Plates($config, new StreamFactory);
    };

    return $next();
});
