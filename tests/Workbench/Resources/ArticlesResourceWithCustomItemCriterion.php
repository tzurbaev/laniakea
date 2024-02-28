<?php

declare(strict_types=1);

namespace Laniakea\Tests\Workbench\Resources;

use Laniakea\Repositories\Criteria\ExactValueCriterion;
use Laniakea\Repositories\Interfaces\RepositoryCriterionInterface;
use Laniakea\Resources\Interfaces\HasItemCriterionInterface;

class ArticlesResourceWithCustomItemCriterion extends ArticlesResource implements HasItemCriterionInterface
{
    public function getItemCriterion(mixed $id): RepositoryCriterionInterface
    {
        return new ExactValueCriterion('title', $id);
    }
}
