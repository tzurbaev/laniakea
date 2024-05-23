<?php

declare(strict_types=1);

namespace Laniakea\Resources\Sorters;

use Laniakea\Repositories\Interfaces\RepositoryQueryBuilderInterface;
use Laniakea\Resources\Interfaces\ResourceSorterInterface;

class ColumnSorter implements ResourceSorterInterface
{
    /**
     * Sort the query by the given column and direction.
     *
     * @param RepositoryQueryBuilderInterface $query
     * @param string                          $column
     * @param string                          $direction
     */
    public function sort(RepositoryQueryBuilderInterface $query, string $column, string $direction): void
    {
        $query->orderBy($column, $direction);
    }
}
