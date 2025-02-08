<?php

declare(strict_types=1);

namespace Laniakea\Transformers\Interfaces;

use Illuminate\Contracts\Pagination\LengthAwarePaginator;

interface TransformationSerializerInterface
{
    /**
     * Serialize root item.
     *
     * @param array $item
     *
     * @return array
     */
    public function getRootItem(array $item): array;

    /**
     * Serialize root collection.
     *
     * @param array $collection
     *
     * @return array
     */
    public function getRootCollection(array $collection): array;

    /**
     * Serialize nested item.
     *
     * @param array $item
     *
     * @return array
     */
    public function getNestedItem(array $item): array;

    /**
     * Serialize nested collection.
     *
     * @param array $collection
     *
     * @return array
     */
    public function getNestedCollection(array $collection): array;

    /**
     * Serialize pagination.
     *
     * @param LengthAwarePaginator $paginator
     *
     * @return array
     */
    public function getPagination(LengthAwarePaginator $paginator): array;
}
