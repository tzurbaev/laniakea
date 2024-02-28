<?php

declare(strict_types=1);

namespace Laniakea\Versions;

use Laniakea\Versions\Interfaces\ApiVersionInterface;

class VersionedContainer
{
    private array $versions = [];
    private array $defaults = [];

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

    public function getVersion(ApiVersionInterface $version): array
    {
        return $this->versions[$version->getName()] ?? $this->defaults;
    }
}
