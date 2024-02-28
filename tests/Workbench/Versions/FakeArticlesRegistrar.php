<?php

declare(strict_types=1);

namespace Laniakea\Tests\Workbench\Versions;

use Laniakea\Resources\Interfaces\ResourceRouteBinderInterface;
use Laniakea\Tests\Workbench\Interfaces\ArticleTransformerInterface;
use Laniakea\Tests\Workbench\Repositories\ArticlesRepository;
use Laniakea\Tests\Workbench\Resources\ArticlesResource;
use Laniakea\Versions\Interfaces\VersionBinderInterface;
use Laniakea\Versions\Interfaces\VersionedResourceRegistrarInterface;

class FakeArticlesRegistrar implements VersionedResourceRegistrarInterface
{
    public function bindRoute(ResourceRouteBinderInterface $binder): void
    {
        $binder->bind('testArticle', ArticlesResource::class, ArticlesRepository::class);
    }

    public function bindVersions(VersionBinderInterface $binder): void
    {
        $binder->bind('v1', [ArticleTransformerInterface::class => ArticleTransformerV1::class]);
        $binder->bind('v2', [ArticleTransformerInterface::class => ArticleTransformerV2::class]);
    }
}
