<?php

declare(strict_types=1);

namespace Laniakea\Resources\Interfaces;

use Laniakea\Repositories\Interfaces\RepositoryCriterionInterface;

interface HasItemCriterionInterface
{
    /**
     * Get custom item criterion for the current resource.
     *
     * @param mixed $id
     *
     * @return RepositoryCriterionInterface
     */
    public function getItemCriterion(mixed $id): RepositoryCriterionInterface;
}
