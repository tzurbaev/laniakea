<?php

declare(strict_types=1);

namespace Laniakea\Versions;

use Laniakea\Versions\Interfaces\VersionBinderInterface;

readonly class VersionBinder implements VersionBinderInterface
{
    public function __construct(private VersionedContainer $container)
    {
        //
    }

    public function bind(string $version, array $bindings, bool $isDefault = false): static
    {
        $this->container->addVersion($version, $bindings, $isDefault);

        return $this;
    }
}
