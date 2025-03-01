<?php

declare(strict_types=1);

namespace Laniakea\Resources\Interfaces;

interface HasDefaultFilterValuesInterface
{
    /**
     * Get default filter values for the current resource.
     *
     * @return array<string, mixed>
     */
    public function getDefaultFilterValues(): array;
}
