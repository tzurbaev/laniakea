<?php

declare(strict_types=1);

namespace Laniakea;

use Illuminate\Contracts\Config\Repository;
use Illuminate\Support\ServiceProvider;
use Laniakea\Forms\FormIdsGenerator;
use Laniakea\Forms\FormsManager;
use Laniakea\Forms\Interfaces\FormIdsGeneratorInterface;
use Laniakea\Forms\Interfaces\FormsManagerInterface;
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

        $this->registerResourceManagerCommands();
        $this->registerResourceManager();

        $container = $this->getFreshVersionedContainer();
        $this->app->instance(VersionedContainer::class, $container);

        $this->registerForms();
    }

    protected function registerResourceManagerCommands(): void
    {
        $this->app->bind(ResourceManagerCommands::class, function () {
            $config = $this->getConfig();

            return new ResourceManagerCommands(
                pagination: $config->get('laniakea.resources.commands.pagination', []),
                list: $config->get('laniakea.resources.commands.list', []),
                item: $config->get('laniakea.resources.commands.item', []),
            );
        });
    }

    protected function registerResourceManager(): void
    {
        $this->app->bind(ResourceManagerInterface::class, function () {
            return $this->app->make(
                $this->getConfig()->get('laniakea.bindings.'.ResourceManagerInterface::class, ResourceManager::class)
            );
        });
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

    protected function registerForms(): void
    {
        $this->app->bind(FormIdsGeneratorInterface::class, function () {
            return $this->app->make(
                $this->getConfig()->get('laniakea.bindings.'.FormIdsGeneratorInterface::class, FormIdsGenerator::class),
            );
        });

        $this->app->bind(FormsManagerInterface::class, function () {
            return $this->app->make(
                $this->getConfig()->get('laniakea.bindings.'.FormsManagerInterface::class, FormsManager::class),
            );
        });
    }

    protected function getConfig(): Repository
    {
        return $this->app->make('config');
    }
}
