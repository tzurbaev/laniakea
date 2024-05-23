<?php

declare(strict_types=1);

namespace Laniakea\Resources\Interfaces;

use Laniakea\Repositories\Interfaces\RepositoryQueryBuilderInterface;

interface ResourceSorterInterface
{
    /**
     * Apply sorting to the query.
     *
     * @param RepositoryQueryBuilderInterface $query
     * @param string                          $column
     * @param string                          $direction
     */
    public function sort(RepositoryQueryBuilderInterface $query, string $column, string $direction): void;
}
