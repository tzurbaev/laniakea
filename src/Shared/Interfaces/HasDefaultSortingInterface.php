<?php

declare(strict_types=1);

namespace Laniakea\Shared\Interfaces;

interface HasDefaultSortingInterface
{
    /**
     * Get the default sorting column.
     *
     * @return string
     */
    public function getDefaultSortingColumn(): string;

    /**
     * Get the default sorting direction.
     *
     * @return string
     */
    public function getDefaultSortingDirection(): string;
}
