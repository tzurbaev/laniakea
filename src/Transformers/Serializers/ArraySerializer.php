<?php

declare(strict_types=1);

namespace Laniakea\Transformers\Serializers;

use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Str;
use Laniakea\Transformers\Interfaces\TransformationSerializerInterface;

class ArraySerializer implements TransformationSerializerInterface
{
    /**
     * Serialize root item.
     *
     * @param array $item
     *
     * @return array
     */
    public function getRootItem(array $item): array
    {
        return $item;
    }

    /**
     * Serialize root collection.
     *
     * @param array $collection
     *
     * @return array
     */
    public function getRootCollection(array $collection): array
    {
        return $collection;
    }

    /**
     * Serialize nested item.
     *
     * @param array $item
     *
     * @return array
     */
    public function getNestedItem(array $item): array
    {
        return $item;
    }

    /**
     * Serialize nested collection.
     *
     * @param array $collection
     *
     * @return array
     */
    public function getNestedCollection(array $collection): array
    {
        return $collection;
    }

    /**
     * Serialize pagination.
     *
     * @param LengthAwarePaginator $paginator
     *
     * @return array
     */
    public function getPagination(LengthAwarePaginator $paginator): array
    {
        return [
            'pagination' => [
                'current' => [
                    'page' => $paginator->currentPage(),
                    'count' => $paginator->count(),
                ],
                'total' => [
                    'pages' => $paginator->lastPage(),
                    'count' => $paginator->total(),
                ],
                'per_page' => $paginator->perPage(),
                'has_previous_page' => $paginator->previousPageUrl() !== null,
                'has_next_page' => $paginator->hasMorePages(),
                'elements' => $this->getPaginationElements($paginator),
            ],
        ];
    }

    /**
     * Get pagination elements list.
     *
     * @param LengthAwarePaginator $paginator
     *
     * @return array
     */
    protected function getPaginationElements(LengthAwarePaginator $paginator): array
    {
        return $paginator->linkCollection()->map(function (array $link) {
            $type = match (true) {
                $link['label'] === '...' => 'placeholder',
                Str::startsWith($link['label'], '&laquo;'), Str::endsWith($link['label'], '&raquo;') => null,
                default => 'page',
            };

            if (is_null($type)) {
                return null;
            }

            return [
                'type' => $type,
                'page' => $type === 'page' ? intval($link['label']) : null,
                'active' => $link['active'],
            ];
        })->reject(null)->values()->toArray();
    }
}
