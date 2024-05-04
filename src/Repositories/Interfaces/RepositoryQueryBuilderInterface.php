<?php

declare(strict_types=1);

namespace Laniakea\Repositories\Interfaces;

use Illuminate\Database\Eloquent\Builder;

interface RepositoryQueryBuilderInterface
{
    /**
     * Add callback that will be executed after criteria apply.
     *
     * @param callable $callback
     *
     * @return $this
     */
    public function afterCriteria(callable $callback): static;

    /**
     * Add callback that will be executed before criteria apply.
     *
     * @param callable $callback
     *
     * @return $this
     */
    public function beforeCriteria(callable $callback): static;

    /**
     * Returns original Eloquent's query builder instance.
     *
     * @return Builder
     */
    public function getQueryBuilder(): Builder;

    /**
     * Replace query criteria.
     *
     * @param array $criteria
     *
     * @return $this
     */
    public function setCriteria(array $criteria): static;

    /**
     * Add one or more criterion to the query.
     *
     * @param array $criteria
     *
     * @return $this
     */
    public function addCriteria(array $criteria): static;

    /**
     * Get query criteria.
     *
     * @return array|RepositoryCriterionInterface[]
     */
    public function getCriteria(): array;

    /**
     * Apply criteria.
     */
    public function applyCriteria(): void;

    /**
     * Load relations.
     *
     * @param array $relations
     *
     * @return $this
     */
    public function with(array $relations): static;

    /**
     * Order results by given column.
     *
     * @param string $column
     * @param string $direction
     *
     * @return $this
     */
    public function orderBy(string $column, string $direction = 'asc'): static;

    /**
     * Limit results.
     *
     * @param int $limit
     *
     * @return $this
     */
    public function limit(int $limit): static;
}
