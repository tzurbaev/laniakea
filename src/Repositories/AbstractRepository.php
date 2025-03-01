<?php

declare(strict_types=1);

namespace Laniakea\Repositories;

use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Collection;
use Laniakea\Repositories\Interfaces\RepositoryInterface;

/**
 * @template T of Model
 *
 * @extends RepositoryInterface<T>
 */
abstract class AbstractRepository implements RepositoryInterface
{
    /**
     * Get Eloquent model class name.
     *
     * @return string
     */
    abstract protected function getModel(): string;

    /**
     * Create fresh instance of the current repositiory's Eloquent model.
     *
     * @return T
     */
    protected function getFreshModel(): Model
    {
        $className = $this->getModel();

        return new $className();
    }

    /**
     * Get fresh instance of Eloquent query builder attached to the current repository's model.
     *
     * @return Builder
     */
    protected function getFreshQuery(): Builder
    {
        return $this->getFreshModel()->newQuery();
    }

    /**
     * Get Eloquent query builder with applied criteria.
     *
     * @param callable|null $callback
     *
     * @return Builder
     */
    protected function getQueryBuilder(?callable $callback): Builder
    {
        $queryBuilder = new RepositoryQueryBuilder($this->getFreshQuery());

        if ($callback) {
            $callback($queryBuilder);
        }

        $queryBuilder->applyCriteria();

        return $queryBuilder->getQueryBuilder();
    }

    /**
     * Create new model.
     *
     * @param array $attributes
     *
     * @return T
     */
    public function create(array $attributes): Model
    {
        $model = $this->getFreshModel();
        $model->fill($attributes);
        $model->save();

        return $model;
    }

    /**
     * Update either provided model instance or existed model by ID.
     *
     * @param T|mixed $id
     * @param array   $attributes
     *
     * @throws ModelNotFoundException
     *
     * @return T
     */
    public function update(mixed $id, array $attributes): Model
    {
        return tap($this->resolveModel($id), function (Model $model) use ($attributes) {
            $model->update($attributes);
        });
    }

    /**
     * Delete either provided model instance or existed model by ID.
     *
     * @param T|mixed $id
     *
     * @throws ModelNotFoundException
     */
    public function delete(mixed $id): void
    {
        $this->resolveModel($id)->delete();
    }

    /**
     * Return provided Eloquent model as-is or resolve it by ID.
     *
     * @param mixed $id
     *
     * @throws ModelNotFoundException
     *
     * @return T
     */
    protected function resolveModel(mixed $id): Model
    {
        if ($id instanceof Model) {
            return $id;
        }

        return $this->findOrFail($id);
    }

    /**
     * Find model by ID.
     *
     * @param mixed         $id
     * @param callable|null $callback
     *
     * @return T|null
     */
    public function find(mixed $id, ?callable $callback = null): ?Model
    {
        return $this->getQueryBuilder($callback)->find($id);
    }

    /**
     * Find model by ID or throw ModelNotFound exception.
     *
     * @param mixed         $id
     * @param callable|null $callback
     *
     * @throws ModelNotFoundException
     *
     * @return T
     */
    public function findOrFail(mixed $id, ?callable $callback = null): Model
    {
        return $this->getQueryBuilder($callback)->findOrFail($id);
    }

    /**
     * Get first model.
     *
     * @param callable|null $callback
     *
     * @return T|null
     */
    public function first(?callable $callback = null): ?Model
    {
        return $this->getQueryBuilder($callback)->first();
    }

    /**
     * Get first model or throw ModelNotFound exception.
     *
     * @param callable|null $callback
     *
     * @throws ModelNotFoundException
     *
     * @return T
     */
    public function firstOrFail(?callable $callback = null): Model
    {
        return $this->getQueryBuilder($callback)->firstOrFail();
    }

    /**
     * List models.
     *
     * @param callable|null $callback
     *
     * @return Collection<int, T>
     */
    public function list(?callable $callback = null): Collection
    {
        return $this->getQueryBuilder($callback)->get();
    }

    /**
     * Paginate models.
     *
     * @param int|null      $page
     * @param int|null      $count
     * @param callable|null $callback
     *
     * @return LengthAwarePaginator<int, T>
     */
    public function paginate(?int $page, ?int $count, ?callable $callback = null): LengthAwarePaginator
    {
        return $this->getQueryBuilder($callback)->paginate(perPage: $count, page: $page);
    }
}
