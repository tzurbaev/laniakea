<?php

declare(strict_types=1);

namespace Laniakea\Repositories;

use Illuminate\Database\Eloquent\Builder;
use Laniakea\Repositories\Interfaces\RepositoryCriterionInterface;
use Laniakea\Repositories\Interfaces\RepositoryQueryBuilderInterface;

class RepositoryQueryBuilder implements RepositoryQueryBuilderInterface
{
    /** @var array|RepositoryCriterionInterface[] */
    private array $criteria = [];

    public function __construct(private readonly Builder $query)
    {
        //
    }

    public function getQueryBuilder(): Builder
    {
        return $this->query;
    }

    public function setCriteria(array $criteria): static
    {
        $this->criteria = $criteria;

        return $this;
    }

    public function addCriteria(array $criteria): static
    {
        $this->criteria = [
            ...$this->criteria,
            ...$criteria,
        ];

        return $this;
    }

    /** @return array|RepositoryCriterionInterface[] */
    public function getCriteria(): array
    {
        return $this->criteria;
    }

    public function with(array $relations): static
    {
        $this->query->with($relations);

        return $this;
    }

    public function orderBy(string $column, string $direction = 'asc'): static
    {
        $this->query->orderBy($column, $direction);

        return $this;
    }

    public function limit(int $limit): static
    {
        $this->query->limit($limit);

        return $this;
    }
}
