<?php

declare(strict_types=1);

namespace Laniakea;

use Illuminate\Support\ServiceProvider;
use Laniakea\Resources\Interfaces\ResourceManagerInterface;
use Laniakea\Resources\Interfaces\ResourceRegistrarInterface;
use Laniakea\Resources\ResourceManager;
use Laniakea\Resources\ResourceManagerCommands;
use Laniakea\Resources\ResourceRouteBinder;
use Laniakea\Versions\Interfaces\VersionedResourceRegistrarInterface;
use Laniakea\Versions\VersionBinder;
use Laniakea\Versions\VersionedContainer;

class LaniakeaServiceProvider extends ServiceProvider
{
    public function boot(VersionedContainer $versionedContainer): void
    {
        $this->publishes([
            __DIR__.'/../config/laniakea.php' => config_path('laniakea.php'),
        ]);

        $this->runRegistrars($versionedContainer);
    }

    public function register(): void
    {
        $this->mergeConfigFrom(__DIR__.'/../config/laniakea.php', 'laniakea');

        $this->bindResourceManagerCommands();
        $this->bindResourceManager();

        $container = $this->getFreshVersionedContainer();
        $this->app->instance(VersionedContainer::class, $container);
    }

    protected function bindResourceManagerCommands(): void
    {
        $this->app->bind(ResourceManagerCommands::class, fn () => new ResourceManagerCommands(
            pagination: config('laniakea.resources.commands.pagination', []),
            list: config('laniakea.resources.commands.list', []),
            item: config('laniakea.resources.commands.item', []),
        ));
    }

    protected function bindResourceManager(): void
    {
        $this->app->bind(ResourceManagerInterface::class, ResourceManager::class);
    }

    protected function getFreshVersionedContainer(): VersionedContainer
    {
        return new VersionedContainer();
    }

    protected function runRegistrars(VersionedContainer $container): void
    {
        $routeBinder = new ResourceRouteBinder();
        $versionBinder = new VersionBinder($container);

        collect(config('laniakea.registrars', []))->each(function (string $name) use ($routeBinder, $versionBinder) {
            /** @var ResourceRegistrarInterface $registrar */
            $registrar = $this->app->make($name);

            $registrar->bindRoute($routeBinder);

            if ($registrar instanceof VersionedResourceRegistrarInterface) {
                $registrar->bindVersions($versionBinder);
            }
        });
    }
}
