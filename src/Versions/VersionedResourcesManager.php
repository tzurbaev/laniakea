<?php

declare(strict_types=1);

namespace Laniakea\Versions;

use Illuminate\Container\Container;
use Laniakea\Versions\Interfaces\ApiVersionInterface;

class VersionedResourcesManager
{
    /**
     * Register bindings for the given version to Laravel's service container.
     *
     * @param ApiVersionInterface $version
     * @param VersionedContainer  $container
     */
    public function register(ApiVersionInterface $version, VersionedContainer $container): void
    {
        $app = Container::getInstance();

        // Make sure we're registering bindings only once.
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
