<?php

declare(strict_types=1);

namespace Laniakea\Resources\Interfaces;

interface HasDefaultFilterValuesInterface
{
    public function getDefaultFilterValues(): array;
}
