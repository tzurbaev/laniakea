<?php

declare(strict_types=1);

use Illuminate\Container\Container;
use Laniakea\Tests\Workbench\Interfaces\ArticleTransformerInterface;
use Laniakea\Tests\Workbench\Versions\ArticleTransformerV1;
use Laniakea\Tests\Workbench\Versions\ArticleTransformerV2;
use Laniakea\Versions\ApiVersion;
use Laniakea\Versions\VersionedContainer;
use Laniakea\Versions\VersionedResourcesManager;

it('should resolve implementations based on API version', function (string $version, string $expectedClass) {
    $container = new VersionedContainer();
    $container->addVersion('v1', [ArticleTransformerInterface::class => ArticleTransformerV1::class]);
    $container->addVersion('v2', [ArticleTransformerInterface::class => ArticleTransformerV2::class]);

    $manager = new VersionedResourcesManager();
    $manager->register(new ApiVersion($version), $container);

    expect(Container::getInstance()->has(ArticleTransformerInterface::class))->toBeTrue();

    $transformer = app(ArticleTransformerInterface::class);
    expect($transformer)->toBeInstanceOf(ArticleTransformerInterface::class)
        ->and($transformer)->toBeInstanceOf($expectedClass);
})->with([
    ['v1', ArticleTransformerV1::class],
    ['v2', ArticleTransformerV2::class],
]);

it('should resolve default implementations for non-existed API versions', function (string $version, string $expectedClass) {
    $container = new VersionedContainer();
    $container->addVersion('v1', [ArticleTransformerInterface::class => ArticleTransformerV1::class], true);
    $container->addVersion('v2', [ArticleTransformerInterface::class => ArticleTransformerV2::class]);

    $manager = new VersionedResourcesManager();
    $manager->register(new ApiVersion($version), $container);

    expect(Container::getInstance()->has(ArticleTransformerInterface::class))->toBeTrue();

    $transformer = app(ArticleTransformerInterface::class);
    expect($transformer)->toBeInstanceOf(ArticleTransformerInterface::class)
        ->and($transformer)->toBeInstanceOf($expectedClass);
})->with([
    ['v1', ArticleTransformerV1::class],
    ['v2', ArticleTransformerV2::class],
    ['v3', ArticleTransformerV1::class],
]);
