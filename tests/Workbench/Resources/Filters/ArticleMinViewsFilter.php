<?php

declare(strict_types=1);

namespace Laniakea\Tests\Workbench\Resources\Filters;

use Laniakea\Repositories\Interfaces\RepositoryQueryBuilderInterface;
use Laniakea\Resources\Interfaces\ResourceFilterInterface;
use Laniakea\Tests\Workbench\Repositories\Criteria\MinViewsCriterion;

class ArticleMinViewsFilter implements ResourceFilterInterface
{
    public function apply(RepositoryQueryBuilderInterface $query, mixed $value, array $values): void
    {
        $query->addCriteria([new MinViewsCriterion(intval($value))]);
    }
}
