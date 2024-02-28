<?php

declare(strict_types=1);

namespace Laniakea\Resources\Interfaces;

interface ResourceInterface
{
    public function getFilters(): array;

    public function getInclusions(): array;

    public function getSorters(): array;
}
