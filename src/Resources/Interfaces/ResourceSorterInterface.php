<?php

declare(strict_types=1);

namespace Laniakea\Resources\Interfaces;

use Laniakea\Repositories\Interfaces\RepositoryQueryBuilderInterface;

interface ResourceSorterInterface
{
    public function sort(RepositoryQueryBuilderInterface $query, string $column, string $direction): void;
}
