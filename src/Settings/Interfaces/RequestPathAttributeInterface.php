<?php

declare(strict_types=1);

namespace Laniakea\Settings\Interfaces;

interface RequestPathAttributeInterface
{
    /**
     * Get request path for current setting.
     *
     * @return string
     */
    public function getPath(): string;
}
