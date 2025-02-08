<?php

declare(strict_types=1);

namespace Laniakea\Transformers\Interfaces;

interface TransformationInterface
{
    /**
     * Create array representation of transformation.
     *
     * @return array
     */
    public function toArray(): array;
}
