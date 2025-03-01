<?php

declare(strict_types=1);

namespace Laniakea\Resources\Interfaces;

use Laniakea\Repositories\Interfaces\RepositoryQueryBuilderInterface;

interface ResourceFilterInterface
{
    /**
     * Apply current filter to the query.
     *
     * @param RepositoryQueryBuilderInterface $query
     * @param mixed                           $value
     * @param array<string, mixed>            $values
     */
    public function apply(RepositoryQueryBuilderInterface $query, mixed $value, array $values): void;
}
