<?php

declare(strict_types=1);

namespace Laniakea\Resources\Sorters;

use Laniakea\Repositories\Interfaces\RepositoryQueryBuilderInterface;
use Laniakea\Resources\Interfaces\ResourceSorterInterface;

readonly class VirtualColumnSorter implements ResourceSorterInterface
{
    public function __construct(private string $column)
    {
        //
    }

    public function sort(RepositoryQueryBuilderInterface $query, string $column, string $direction): void
    {
        $query->orderBy($this->column, $direction);
    }
}
