<?php

declare(strict_types=1);

use Laniakea\Versions\ApiVersion;
use Laniakea\Versions\VersionedContainer;

it('should bind versions', function () {
    $container = new VersionedContainer();
    $container->addVersion('v1', ['Abstract' => 'Concrete']);

    expect($container->getVersion(new ApiVersion('v1')))->toHaveCount(1)
        ->and($container->getVersion(new ApiVersion('v1')))->toHaveKey('Abstract');
});

it('should bind multiple versions', function () {
    $container = new VersionedContainer();
    $container->addVersion('v1', ['Abstract' => 'ConcreteV1']);
    $container->addVersion('v2', ['Abstract' => 'ConcreteV2']);

    expect($container->getVersion(new ApiVersion('v1')))->toHaveCount(1)
        ->and($container->getVersion(new ApiVersion('v1')))->toHaveKey('Abstract')
        ->and($container->getVersion(new ApiVersion('v1'))['Abstract'])->toBe('ConcreteV1')
        ->and($container->getVersion(new ApiVersion('v2')))->toHaveCount(1)
        ->and($container->getVersion(new ApiVersion('v2')))->toHaveKey('Abstract')
        ->and($container->getVersion(new ApiVersion('v2'))['Abstract'])->toBe('ConcreteV2');
});

it('should resolve default bindings for non-existed version', function () {
    $container = new VersionedContainer();
    $container->addVersion('v1', ['Abstract' => 'ConcreteV1'], true);

    expect($container->getVersion(new ApiVersion('v1')))->toHaveCount(1)
        ->and($container->getVersion(new ApiVersion('v1')))->toHaveKey('Abstract')
        ->and($container->getVersion(new ApiVersion('v1'))['Abstract'])->toBe('ConcreteV1')
        ->and($container->getVersion(new ApiVersion('v2')))->toHaveCount(1)
        ->and($container->getVersion(new ApiVersion('v2')))->toHaveKey('Abstract')
        ->and($container->getVersion(new ApiVersion('v2'))['Abstract'])->toBe('ConcreteV1');
});

it('should resolve nothing for non-existed version if no defaults were saved', function () {
    $container = new VersionedContainer();
    $container->addVersion('v1', ['Abstract' => 'ConcreteV1']);

    expect($container->getVersion(new ApiVersion('v1')))->toHaveCount(1)
        ->and($container->getVersion(new ApiVersion('v1')))->toHaveKey('Abstract')
        ->and($container->getVersion(new ApiVersion('v1'))['Abstract'])->toBe('ConcreteV1')
        ->and($container->getVersion(new ApiVersion('v2')))->toHaveCount(0);
});
