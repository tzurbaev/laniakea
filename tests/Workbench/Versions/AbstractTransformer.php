<?php

declare(strict_types=1);

namespace Laniakea\Tests\Workbench\Versions;

abstract class AbstractTransformer
{
    protected function getTransformedObject(array $primary, array $optional = []): array
    {
        return array_merge($primary, array_filter($optional));
    }
}
