<?php

declare(strict_types=1);

namespace Laniakea\Tests\Workbench\Resources;

use Laniakea\Resources\AbstractResourceContainer;
use Laniakea\Tests\Workbench\Repositories\ArticlesRepository;
use Laniakea\Tests\Workbench\Versions\ArticleTransformerV1;

/**
 * @method ArticlesResource     getResource()
 * @method ArticlesRepository   getRepository()
 * @method ArticleTransformerV1 getTransformer()
 */
class ArticlesContainer extends AbstractResourceContainer
{
    protected string $resource = ArticlesResource::class;
    protected string $repository = ArticlesRepository::class;
    protected string $transformer = ArticleTransformerV1::class;
}
