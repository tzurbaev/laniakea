<?php

declare(strict_types=1);

namespace Laniakea\Transformers\Entities;

readonly class TransformerInclusion
{
    public function __construct(private string $name, private bool $default, private string $method)
    {
        //
    }

    /**
     * Get transformer inclusion name.
     *
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * Check if transformer inclusion is default.
     *
     * @return bool
     */
    public function isDefault(): bool
    {
        return $this->default;
    }

    /**
     * Get transformer method name for inclusion.
     *
     * @return string
     */
    public function getMethod(): string
    {
        return $this->method;
    }
}
