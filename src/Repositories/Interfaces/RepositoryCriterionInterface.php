<?php

declare(strict_types=1);

namespace Laniakea\Repositories\Interfaces;

use Illuminate\Database\Eloquent\Builder;

interface RepositoryCriterionInterface
{
    public function apply(Builder $query): void;
}
