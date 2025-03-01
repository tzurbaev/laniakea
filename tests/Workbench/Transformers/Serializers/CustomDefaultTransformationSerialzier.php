<?php

declare(strict_types=1);

namespace Laniakea\Tests\Workbench\Transformers\Serializers;

use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Laniakea\Transformers\Interfaces\TransformationSerializerInterface;

class CustomDefaultTransformationSerialzier implements TransformationSerializerInterface
{
    public function getRootItem(array $item): array
    {
        return [
            'type' => 'item',
            'data' => $item,
        ];
    }

    public function getRootCollection(array $collection): array
    {
        return [
            'type' => 'collection',
            'data' => $collection,
        ];
    }

    public function getNestedItem(array $item): array
    {
        return [
            'type' => 'nested_item',
            'data' => $item,
        ];
    }

    public function getNestedCollection(array $collection): array
    {
        return [
            'type' => 'nested_collection',
            'data' => $collection,
        ];
    }

    public function getPagination(LengthAwarePaginator $paginator): array
    {
        return [
            'type' => 'pagination',
            'data' => [
                'count' => $paginator->total(),
                'pages' => $paginator->lastPage(),
            ],
        ];
    }
}
