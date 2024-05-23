<?php

declare(strict_types=1);

namespace Laniakea\Versions\Interfaces;

interface ApiVersionInterface
{
    /**
     * Get API version name.
     *
     * @return string
     */
    public function getName(): string;
}
