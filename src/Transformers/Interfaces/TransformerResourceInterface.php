<?php

declare(strict_types=1);

namespace Laniakea\Transformers\Interfaces;

interface TransformerResourceInterface
{
    /**
     * Get resource data.
     *
     * @return mixed
     */
    public function getData(): mixed;

    /**
     * Get resource transformer.
     *
     * @return mixed
     */
    public function getTransformer(): mixed;
}
