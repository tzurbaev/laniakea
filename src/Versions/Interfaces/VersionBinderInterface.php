<?php

declare(strict_types=1);

namespace Laniakea\Versions\Interfaces;

interface VersionBinderInterface
{
    /**
     * Bind abstracts to implementations for a specific API version.
     *
     * @param string $version
     * @param array  $bindings
     * @param bool   $isDefault
     *
     * @return $this
     */
    public function bind(string $version, array $bindings, bool $isDefault = false): static;
}
