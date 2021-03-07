<?php

use Projek\Slim\PlatesProvider;

use function Kahlan\describe;

describe(PlatesProvider::class, function () {
    it('Should be truthy', function () {
        expect(true)->toBeTruthy();
    });
});
