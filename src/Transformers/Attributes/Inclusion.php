<?php

declare(strict_types=1);

namespace Laniakea\Transformers\Attributes;

#[\Attribute]
readonly class Inclusion
{
    public function __construct(private string $name, private bool $default = false)
    {
        //
    }

    /**
     * Get inclusion name.
     *
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * Check if inclusion is default.
     *
     * @return bool
     */
    public function isDefault(): bool
    {
        return $this->default;
    }
}
