<?php

declare(strict_types=1);

namespace Laniakea\Tests\Workbench\Entities;

readonly class NestedTransformationEntity
{
    public function __construct(public string $name, public ?NestedTransformationEntity $next = null)
    {
        //
    }
}
