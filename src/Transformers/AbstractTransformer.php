<?php

declare(strict_types=1);

namespace Laniakea\Transformers;

use Laniakea\Transformers\Resources\CollectionResource;
use Laniakea\Transformers\Resources\ItemResource;
use Laniakea\Transformers\Resources\PrimitiveResource;

abstract class AbstractTransformer
{
    /**
     * Create nested item resource.
     *
     * @param mixed $data
     * @param mixed $transformer
     *
     * @return ItemResource
     */
    protected function item(mixed $data, mixed $transformer): ItemResource
    {
        return new ItemResource($data, $transformer);
    }

    /**
     * Create nested collection resource.
     *
     * @param iterable $data
     * @param mixed    $transformer
     *
     * @return CollectionResource
     */
    protected function collection(iterable $data, mixed $transformer): CollectionResource
    {
        return new CollectionResource($data, $transformer);
    }

    /**
     * Create primitive resource.
     *
     * @param mixed $data
     *
     * @return PrimitiveResource
     */
    protected function primitive(mixed $data): PrimitiveResource
    {
        return new PrimitiveResource($data);
    }
}
