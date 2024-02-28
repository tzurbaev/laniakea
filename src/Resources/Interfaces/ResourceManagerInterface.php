<?php

declare(strict_types=1);

namespace Laniakea\Resources\Interfaces;

use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Collection;
use Laniakea\Repositories\Interfaces\RepositoryInterface;

interface ResourceManagerInterface
{
    /**
     * Get paginator for the given resource.
     *
     * @param ResourceRequestInterface $request
     * @param ResourceInterface        $resource
     * @param RepositoryInterface      $repository
     * @param callable|null            $callback
     * @param array                    $context
     *
     * @return LengthAwarePaginator
     */
    public function getPaginator(
        ResourceRequestInterface $request,
        ResourceInterface $resource,
        RepositoryInterface $repository,
        ?callable $callback = null,
        array $context = [],
    ): LengthAwarePaginator;

    /**
     * Get collection for the given resource.
     *
     * @param ResourceRequestInterface $request
     * @param ResourceInterface        $resource
     * @param RepositoryInterface      $repository
     * @param callable|null            $callback
     * @param array                    $context
     *
     * @return Collection
     */
    public function getList(
        ResourceRequestInterface $request,
        ResourceInterface $resource,
        RepositoryInterface $repository,
        ?callable $callback = null,
        array $context = [],
    ): Collection;

    /**
     * Find single item by ID.
     *
     * @param mixed                    $id
     * @param ResourceRequestInterface $request
     * @param ResourceInterface        $resource
     * @param RepositoryInterface      $repository
     * @param array                    $context
     *
     * @throws ModelNotFoundException
     *
     * @return Model
     */
    public function getItem(
        mixed $id,
        ResourceRequestInterface $request,
        ResourceInterface $resource,
        RepositoryInterface $repository,
        array $context = [],
    ): Model;
}
