<?php

declare(strict_types=1);

namespace Laniakea\Repositories;

use Illuminate\Database\Eloquent\Builder;
use Laniakea\Repositories\Enums\RepositoryCallbackType;
use Laniakea\Repositories\Interfaces\RepositoryCriterionInterface;
use Laniakea\Repositories\Interfaces\RepositoryQueryBuilderInterface;

class RepositoryQueryBuilder implements RepositoryQueryBuilderInterface
{
    /** @var array|RepositoryCriterionInterface[] */
    private array $criteria = [];

    /** @var array|callable[] */
    private array $callbacks = [];

    public function __construct(private readonly Builder $query)
    {
        //
    }

    public function getQueryBuilder(): Builder
    {
        return $this->query;
    }

    public function afterCriteria(callable $callback): static
    {
        return $this->addCallback(RepositoryCallbackType::AFTER_CRITERIA, $callback);
    }

    public function beforeCriteria(callable $callback): static
    {
        return $this->addCallback(RepositoryCallbackType::BEFORE_CRITERIA, $callback);
    }

    protected function addCallback(RepositoryCallbackType $type, callable $callback): static
    {
        if (!isset($this->callbacks[$type->value])) {
            $this->callbacks[$type->value] = [];
        }

        $this->callbacks[$type->value][] = $callback;

        return $this;
    }

    protected function runCallbacks(RepositoryCallbackType $type): void
    {
        if (!isset($this->callbacks[$type->value])) {
            return;
        }

        foreach ($this->callbacks[$type->value] as $callback) {
            $callback($this);
        }
    }

    public function applyCriteria(): void
    {
        $this->runCallbacks(RepositoryCallbackType::BEFORE_CRITERIA);

        foreach ($this->criteria as $criterion) {
            $criterion->apply($this->query);
        }

        $this->runCallbacks(RepositoryCallbackType::AFTER_CRITERIA);
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
