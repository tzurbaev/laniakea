<?php

declare(strict_types=1);

namespace Laniakea\Resources\Interfaces;

interface HasDefaultFilterValuesInterface
{
    /**
     * Get default filter values for the current resource.
     *
     * @return array
     */
    public function getDefaultFilterValues(): array;
}
