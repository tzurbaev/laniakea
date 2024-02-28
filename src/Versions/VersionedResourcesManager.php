<?php

declare(strict_types=1);

namespace Laniakea\Versions;

use Illuminate\Container\Container;
use Laniakea\Versions\Interfaces\ApiVersionInterface;

class VersionedResourcesManager
{
    public function register(ApiVersionInterface $version, VersionedContainer $container): void
    {
        $app = Container::getInstance();

        if ($app->has('laniakea.versions.registered')) {
            return;
        }

        $bindings = $container->getVersion($version);

        if (!count($bindings)) {
            return;
        }

        foreach ($bindings as $abstract => $concrete) {
            $app->bind($abstract, $concrete);
        }

        $app->instance('laniakea.versions.registered', true);
    }
}
