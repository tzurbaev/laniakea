<?php

declare(strict_types=1);

namespace Laniakea\Versions;

use Laniakea\Versions\Interfaces\ApiVersionInterface;

readonly class ApiVersion implements ApiVersionInterface
{
    public function __construct(private string $name)
    {
        //
    }

    public function getName(): string
    {
        return $this->name;
    }
}
