<?php

declare(strict_types=1);

namespace Laniakea\Resources\Sorters;

use Laniakea\Repositories\Interfaces\RepositoryQueryBuilderInterface;
use Laniakea\Resources\Interfaces\ResourceSorterInterface;

readonly class VirtualColumnSorter implements ResourceSorterInterface
{
    /**
     * @param string $column The real database column to sort by.
     */
    public function __construct(private string $column)
    {
        //
    }

    /**
     * Sort the query by the virtual column and direction.
     *
     * @param RepositoryQueryBuilderInterface $query
     * @param string                          $column
     * @param string                          $direction
     */
    public function sort(RepositoryQueryBuilderInterface $query, string $column, string $direction): void
    {
        $query->orderBy($this->column, $direction);
    }
}
