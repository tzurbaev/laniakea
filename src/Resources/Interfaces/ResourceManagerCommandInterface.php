<?php

declare(strict_types=1);

namespace Laniakea\Resources\Interfaces;

use Laniakea\Repositories\Interfaces\RepositoryQueryBuilderInterface;

interface ResourceManagerCommandInterface
{
    public function run(RepositoryQueryBuilderInterface $query, ResourceContextInterface $context): void;
}
