<?php

declare(strict_types=1);

namespace Laniakea\Resources\Interfaces;

use Laniakea\Repositories\Interfaces\RepositoryQueryBuilderInterface;

interface ResourceManagerCommandInterface
{
    /**
     * Execute resource manager command.
     *
     * @param RepositoryQueryBuilderInterface $query
     * @param ResourceContextInterface        $context
     */
    public function run(RepositoryQueryBuilderInterface $query, ResourceContextInterface $context): void;
}
