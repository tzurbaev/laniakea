<?php

declare(strict_types=1);

namespace Laniakea\Resources\Interfaces;

use Laniakea\Repositories\Interfaces\RepositoryQueryBuilderInterface;

interface ResourceFilterInterface
{
    public function apply(RepositoryQueryBuilderInterface $query, mixed $value, array $values): void;
}
