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

    /**
     * Bind abstracts to implementations for a specific API version.
     *
     * @param string $version
     * @param array  $bindings
     * @param bool   $isDefault
     *
     * @return $this
     */
    public function bind(string $version, array $bindings, bool $isDefault = false): static
    {
        $this->container->addVersion($version, $bindings, $isDefault);

        return $this;
    }
}
