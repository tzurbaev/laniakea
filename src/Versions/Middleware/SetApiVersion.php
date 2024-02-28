<?php

declare(strict_types=1);

namespace Laniakea\Versions\Middleware;

use Illuminate\Container\Container;
use Illuminate\Http\Request;
use Laniakea\Versions\ApiVersion;
use Laniakea\Versions\Interfaces\ApiVersionInterface;
use Laniakea\Versions\VersionedContainer;
use Laniakea\Versions\VersionedResourcesManager;

class SetApiVersion
{
    public function handle(Request $request, callable $next, string $version)
    {
        $app = Container::getInstance();
        $apiVersion = new ApiVersion($version);

        $app->instance(ApiVersionInterface::class, $apiVersion);

        /** @var VersionedResourcesManager $manager */
        $manager = $app->get(VersionedResourcesManager::class);

        /** @var VersionedContainer $versionedContainer */
        $versionedContainer = $app->get(VersionedContainer::class);

        $manager->register($apiVersion, $versionedContainer);

        return $next($request);
    }
}
