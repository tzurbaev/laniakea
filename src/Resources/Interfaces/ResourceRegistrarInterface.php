<?php

declare(strict_types=1);

namespace Laniakea\Resources\Interfaces;

interface ResourceRegistrarInterface
{
    public function bindRoute(ResourceRouteBinderInterface $binder): void;
}
