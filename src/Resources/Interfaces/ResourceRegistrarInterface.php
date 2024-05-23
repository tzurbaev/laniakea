<?php

declare(strict_types=1);

namespace Laniakea\Resources\Interfaces;

interface ResourceRegistrarInterface
{
    /**
     * Bind current resource to the router.
     *
     * @param ResourceRouteBinderInterface $binder
     */
    public function bindRoute(ResourceRouteBinderInterface $binder): void;
}
