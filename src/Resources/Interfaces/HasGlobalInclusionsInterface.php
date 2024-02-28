<?php

declare(strict_types=1);

namespace Laniakea\Resources\Interfaces;

interface HasGlobalInclusionsInterface
{
    public function getGlobalInclusions(): array;
}
