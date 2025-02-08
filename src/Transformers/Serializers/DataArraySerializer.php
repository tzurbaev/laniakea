<?php

declare(strict_types=1);

namespace Laniakea\Transformers\Serializers;

class DataArraySerializer extends ArraySerializer
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
        return ['data' => $item];
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
        return ['data' => $collection];
    }
}
