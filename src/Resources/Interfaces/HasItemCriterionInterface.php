<?php

declare(strict_types=1);

namespace Laniakea\Resources\Interfaces;

use Laniakea\Repositories\Interfaces\RepositoryCriterionInterface;

interface HasItemCriterionInterface
{
    public function getItemCriterion(mixed $id): RepositoryCriterionInterface;
}
