<?php

declare(strict_types=1);

namespace Laniakea\Tests\Workbench\Resources\Filters;

use Laniakea\Repositories\Criteria\ExactValueCriterion;
use Laniakea\Repositories\Interfaces\RepositoryQueryBuilderInterface;
use Laniakea\Resources\Interfaces\ResourceFilterInterface;

class ArticleCategoryFilter implements ResourceFilterInterface
{
    public function apply(RepositoryQueryBuilderInterface $query, mixed $value, array $values): void
    {
        if (is_null($value)) {
            return;
        }

        $query->addCriteria([new ExactValueCriterion('article_category_id', intval($value))]);
    }
}
