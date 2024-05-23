<?php

declare(strict_types=1);

namespace Laniakea\Repositories\Interfaces;

use Illuminate\Database\Eloquent\Builder;

interface RepositoryCriterionInterface
{
    /**
     * Apply the criterion to the query.
     *
     * @param Builder $query
     */
    public function apply(Builder $query): void;
}
