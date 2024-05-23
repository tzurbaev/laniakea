<?php

declare(strict_types=1);

namespace Laniakea\Versions\Interfaces;

use Laniakea\Resources\Interfaces\ResourceRegistrarInterface;

interface VersionedResourceRegistrarInterface extends ResourceRegistrarInterface
{
    /**
     * Bind abstracts to implementations for a specific API version.
     *
     * @param VersionBinderInterface $binder
     */
    public function bindVersions(VersionBinderInterface $binder): void;
}
