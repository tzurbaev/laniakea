<?php

declare(strict_types=1);

namespace Laniakea\Resources\Sorters;

use Laniakea\Repositories\Interfaces\RepositoryQueryBuilderInterface;
use Laniakea\Resources\Interfaces\ResourceSorterInterface;

class ColumnSorter implements ResourceSorterInterface
{
    public function sort(RepositoryQueryBuilderInterface $query, string $column, string $direction): void
    {
        $query->orderBy($column, $direction);
    }
}
