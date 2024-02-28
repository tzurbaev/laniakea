<?php

declare(strict_types=1);

namespace Laniakea\Tests\Workbench\Repositories\Criteria;

use Illuminate\Database\Eloquent\Builder;
use Laniakea\Repositories\Interfaces\RepositoryCriterionInterface;

readonly class MinViewsCriterion implements RepositoryCriterionInterface
{
    public function __construct(private int $minViews)
    {
        //
    }

    public function apply(Builder $query): void
    {
        $query->where('views', '>=', $this->minViews);
    }
}
