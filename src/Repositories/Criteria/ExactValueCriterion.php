<?php

declare(strict_types=1);

namespace Laniakea\Repositories\Criteria;

use Illuminate\Database\Eloquent\Builder;
use Laniakea\Repositories\Interfaces\RepositoryCriterionInterface;

readonly class ExactValueCriterion implements RepositoryCriterionInterface
{
    public function __construct(private string $column, private mixed $value)
    {
        //
    }

    public function apply(Builder $query): void
    {
        if (is_null($this->value)) {
            $query->whereNull($this->column);
        } elseif (is_array($this->value)) {
            $query->whereIn($this->column, $this->value);
        } else {
            $query->where($this->column, $this->value);
        }
    }
}
