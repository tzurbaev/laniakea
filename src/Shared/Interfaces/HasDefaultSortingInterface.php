<?php

declare(strict_types=1);

namespace Laniakea\Shared\Interfaces;

interface HasDefaultSortingInterface
{
    public function getDefaultSortingColumn(): string;

    public function getDefaultSortingDirection(): string;
}
