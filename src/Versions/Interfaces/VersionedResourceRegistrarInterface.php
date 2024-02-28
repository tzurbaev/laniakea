<?php

declare(strict_types=1);

namespace Laniakea\Versions\Interfaces;

use Laniakea\Resources\Interfaces\ResourceRegistrarInterface;

interface VersionedResourceRegistrarInterface extends ResourceRegistrarInterface
{
    public function bindVersions(VersionBinderInterface $binder): void;
}
