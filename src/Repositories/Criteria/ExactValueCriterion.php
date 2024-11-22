<?php

declare(strict_types=1);

namespace Laniakea\Repositories\Criteria;

use Illuminate\Database\Eloquent\Builder;
use Laniakea\Repositories\Interfaces\RepositoryCriterionInterface;

readonly class ExactValueCriterion implements RepositoryCriterionInterface
{
    /**
     * @param string           $column
     * @param array|mixed|null $value
     */
    public function __construct(private string $column, private mixed $value)
    {
        //
    }

    /**
     * This will apply either whereNull, whereIn, or where depending on the value.
     *
     * @param Builder $query
     */
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
