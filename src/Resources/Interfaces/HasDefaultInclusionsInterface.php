<?php

declare(strict_types=1);

namespace Laniakea\Resources\Interfaces;

interface HasDefaultInclusionsInterface
{
    /**
     * Get default inclusions list for the current resource.
     *
     * @return array
     */
    public function getDefaultInclusions(): array;
}
