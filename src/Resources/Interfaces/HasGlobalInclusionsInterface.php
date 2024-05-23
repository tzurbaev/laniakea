<?php

declare(strict_types=1);

namespace Laniakea\Resources\Interfaces;

interface HasGlobalInclusionsInterface
{
    /**
     * Get inclusions list that should be loaded in all queries.
     *
     * @return array
     */
    public function getGlobalInclusions(): array;
}
