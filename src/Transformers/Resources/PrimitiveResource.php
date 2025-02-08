<?php

declare(strict_types=1);

namespace Laniakea\Transformers\Resources;

use Laniakea\Transformers\Interfaces\TransformerResourceInterface;

readonly class PrimitiveResource implements TransformerResourceInterface
{
    public function __construct(private mixed $data)
    {
        //
    }

    /**
     * Get resource data.
     *
     * @return mixed
     */
    public function getData(): mixed
    {
        return $this->data;
    }

    /**
     * Get resource transformer.
     *
     * @return mixed
     */
    public function getTransformer(): mixed
    {
        return null;
    }
}
