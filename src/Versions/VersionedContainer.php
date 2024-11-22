<?php

declare(strict_types=1);

namespace Laniakea\Versions;

use Laniakea\Versions\Interfaces\ApiVersionInterface;

class VersionedContainer
{
    /**
     * List of versioned bindings.
     *
     * @var array
     */
    private array $versions = [];

    /**
     * Default version bindings.
     *
     * @var array
     */
    private array $defaults = [];

    /**
     * Add versioned bindings to the container.
     *
     * @param string $version
     * @param array  $bindings
     * @param bool   $isDefault
     *
     * @return $this
     */
    public function addVersion(string $version, array $bindings, bool $isDefault = false): static
    {
        $this->versions[$version] = [
            ...$this->versions[$version] ?? [],
            ...$bindings,
        ];

        if ($isDefault) {
            $this->defaults = [
                ...$this->defaults,
                ...$bindings,
            ];
        }

        return $this;
    }

    /**
     * Get bindings for the requested version or default version's ones.
     *
     * @param ApiVersionInterface $version
     *
     * @return array
     */
    public function getVersion(ApiVersionInterface $version): array
    {
        return $this->versions[$version->getName()] ?? $this->defaults;
    }
}
