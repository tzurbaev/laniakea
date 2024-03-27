<?php

declare(strict_types=1);

namespace Laniakea\Settings;

use Laniakea\Settings\Interfaces\RequestPathAttributeInterface;

#[\Attribute]
readonly class RequestPath implements RequestPathAttributeInterface
{
    public function __construct(private string $path)
    {
        //
    }

    public function getPath(): string
    {
        return $this->path;
    }
}
