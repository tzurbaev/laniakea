<?php

declare(strict_types=1);

namespace Laniakea\Tests\Workbench\Repositories\Criteria;

use Illuminate\Database\Eloquent\Builder;
use Laniakea\Repositories\Interfaces\RepositoryCriterionInterface;

readonly class SearchCriterion implements RepositoryCriterionInterface
{
    public function __construct(private array $columns, private string $query)
    {
        //
    }

    public function apply(Builder $query): void
    {
        $query->where(function (Builder $subQuery) {
            foreach ($this->columns as $column) {
                $subQuery->orWhere($column, 'like', '%'.$this->query.'%');
            }
        });
    }
}
