<?php

declare(strict_types=1);

namespace Laniakea\Repositories;

use Illuminate\Database\Eloquent\Builder;
use Laniakea\Repositories\Enums\RepositoryCallbackType;
use Laniakea\Repositories\Interfaces\RepositoryCriterionInterface;
use Laniakea\Repositories\Interfaces\RepositoryQueryBuilderInterface;

class RepositoryQueryBuilder implements RepositoryQueryBuilderInterface
{
    /**
     * List of repository query builder criteria.
     *
     * @var array|RepositoryCriterionInterface[]
     */
    private array $criteria = [];

    /**
     * List of repository query builder callbacks.
     *
     * @var array|callable[]
     */
    private array $callbacks = [];

    public function __construct(private readonly Builder $query)
    {
        //
    }

    /**
     * Add callback that will be executed after criteria apply.
     *
     * @param callable $callback
     *
     * @return $this
     */
    public function afterCriteria(callable $callback): static
    {
        return $this->addCallback(RepositoryCallbackType::AFTER_CRITERIA, $callback);
    }

    /**
     * Add callback that will be executed before criteria apply.
     *
     * @param callable $callback
     *
     * @return $this
     */
    public function beforeCriteria(callable $callback): static
    {
        return $this->addCallback(RepositoryCallbackType::BEFORE_CRITERIA, $callback);
    }

    /**
     * Add new callback to the list of callbacks.
     *
     * @param RepositoryCallbackType $type
     * @param callable               $callback
     *
     * @return $this
     */
    protected function addCallback(RepositoryCallbackType $type, callable $callback): static
    {
        if (!isset($this->callbacks[$type->value])) {
            $this->callbacks[$type->value] = [];
        }

        $this->callbacks[$type->value][] = $callback;

        return $this;
    }

    /**
     * Execute given type of callbacks.
     *
     * @param RepositoryCallbackType $type
     */
    protected function runCallbacks(RepositoryCallbackType $type): void
    {
        if (!isset($this->callbacks[$type->value])) {
            return;
        }

        foreach ($this->callbacks[$type->value] as $callback) {
            $callback($this);
        }
    }

    /**
     * Returns original Eloquent's query builder instance.
     *
     * @return Builder
     */
    public function getQueryBuilder(): Builder
    {
        return $this->query;
    }

    /**
     * Replace query criteria.
     *
     * @param array $criteria
     *
     * @return $this
     */
    public function setCriteria(array $criteria): static
    {
        $this->criteria = $criteria;

        return $this;
    }

    /**
     * Add one or more criterion to the query.
     *
     * @param array $criteria
     *
     * @return $this
     */
    public function addCriteria(array $criteria): static
    {
        $this->criteria = [
            ...$this->criteria,
            ...$criteria,
        ];

        return $this;
    }

    /**
     * Get query criteria.
     *
     * @return array|RepositoryCriterionInterface[]
     */
    public function getCriteria(): array
    {
        return $this->criteria;
    }

    /**
     * Apply criteria to the query builder.
     */
    public function applyCriteria(): void
    {
        $this->runCallbacks(RepositoryCallbackType::BEFORE_CRITERIA);

        foreach ($this->criteria as $criterion) {
            $criterion->apply($this->query);
        }

        $this->runCallbacks(RepositoryCallbackType::AFTER_CRITERIA);
    }

    /**
     * Eagerly load Eloquent model's relations.
     *
     * @param array $relations
     *
     * @return $this
     */
    public function with(array $relations): static
    {
        $this->query->with($relations);

        return $this;
    }

    /**
     * Order results by given column.
     *
     * @param string $column
     * @param string $direction
     *
     * @return $this
     */
    public function orderBy(string $column, string $direction = 'asc'): static
    {
        $this->query->orderBy($column, $direction);

        return $this;
    }

    /**
     * Limit results.
     *
     * @param int $limit
     *
     * @return $this
     */
    public function limit(int $limit): static
    {
        $this->query->limit($limit);

        return $this;
    }
}
