<?php

declare(strict_types=1);

use Laniakea\Tests\Workbench\Repositories\ArticlesRepository;
use Laniakea\Tests\Workbench\Resources\ArticlesContainer;
use Laniakea\Tests\Workbench\Resources\ArticlesResource;
use Laniakea\Tests\Workbench\Resources\CustomArticlesContainer;
use Laniakea\Tests\Workbench\Versions\ArticleTransformerV1;

it('should resolve instances inside resource containers', function () {
    $container = new ArticlesContainer();

    expect($container->getResource())->toBeInstanceOf(ArticlesResource::class)
        ->and($container->getRepository())->toBeInstanceOf(ArticlesRepository::class)
        ->and($container->getTransformer())->toBeInstanceOf(ArticleTransformerV1::class);
});

it('should resolve instances inside extended resource containers', function () {
    $container = new CustomArticlesContainer();

    expect($container->getResource())->toBeInstanceOf(ArticlesResource::class)
        ->and($container->getRepository())->toBeInstanceOf(ArticlesRepository::class)
        ->and($container->getTransformer())->toBeInstanceOf(ArticleTransformerV1::class);
});
