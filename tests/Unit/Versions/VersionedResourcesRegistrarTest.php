<?php

declare(strict_types=1);

use Laniakea\Tests\Workbench\Versions\ArticlesRegistrar;
use Laniakea\Versions\ApiVersion;
use Laniakea\Versions\VersionBinder;
use Laniakea\Versions\VersionedContainer;

it('should register version bindings to underlying container', function () {
    $container = new VersionedContainer();
    $binder = new VersionBinder($container);
    expect($container->getVersion(new ApiVersion('v1')))->toBeEmpty()
        ->and($container->getVersion(new ApiVersion('v2')))->toBeEmpty();

    $registrar = new ArticlesRegistrar();
    $registrar->bindVersions($binder);

    expect($container->getVersion(new ApiVersion('v1')))->toHaveCount(1)
        ->and($container->getVersion(new ApiVersion('v2')))->toHaveCount(1);
});
