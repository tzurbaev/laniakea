<?php

declare(strict_types=1);

namespace Laniakea\Tests\Workbench\Resources;

use Laniakea\Repositories\Interfaces\RepositoryInterface;
use Laniakea\Resources\AbstractResourceContainer;
use Laniakea\Resources\Interfaces\ResourceInterface;
use Laniakea\Tests\Workbench\Repositories\ArticlesRepository;
use Laniakea\Tests\Workbench\Versions\ArticleTransformerV1;

class CustomArticlesContainer extends AbstractResourceContainer
{
    public function getResource(): ResourceInterface
    {
        return new ArticlesResource();
    }

    public function getRepository(): RepositoryInterface
    {
        return new ArticlesRepository();
    }

    public function getTransformer(): ArticleTransformerV1
    {
        return new ArticleTransformerV1();
    }
}
