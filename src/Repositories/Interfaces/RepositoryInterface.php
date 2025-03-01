<?php

declare(strict_types=1);

namespace Laniakea\Repositories\Interfaces;

use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Collection;

/**
 * @template T of Model
 */
interface RepositoryInterface
{
    /**
     * Create new model.
     *
     * @param array $attributes
     *
     * @return T
     */
    public function create(array $attributes): Model;

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
    public function update(mixed $id, array $attributes): Model;

    /**
     * Delete either provided model instance or existed model by ID.
     *
     * @param T|mixed $id
     *
     * @throws ModelNotFoundException
     */
    public function delete(mixed $id): void;

    /**
     * Find model by ID.
     *
     * @param mixed         $id
     * @param callable|null $callback
     *
     * @return T|null
     */
    public function find(mixed $id, ?callable $callback = null): ?Model;

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
    public function findOrFail(mixed $id, ?callable $callback = null): Model;

    /**
     * Get first model.
     *
     * @param callable|null $callback
     *
     * @return T|null
     */
    public function first(?callable $callback = null): ?Model;

    /**
     * Get first model or throw ModelNotFound exception.
     *
     * @param callable|null $callback
     *
     * @throws ModelNotFoundException
     *
     * @return T
     */
    public function firstOrFail(?callable $callback = null): Model;

    /**
     * List models.
     *
     * @param callable|null $callback
     *
     * @return Collection<int, T>
     */
    public function list(?callable $callback = null): Collection;

    /**
     * Paginate models.
     *
     * @param int|null      $page
     * @param int|null      $count
     * @param callable|null $callback
     *
     * @return LengthAwarePaginator<int, T>
     */
    public function paginate(?int $page, ?int $count, ?callable $callback = null): LengthAwarePaginator;
}
