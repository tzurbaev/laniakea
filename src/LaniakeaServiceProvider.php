<?php

declare(strict_types=1);

namespace Laniakea;

use Illuminate\Contracts\Config\Repository;
use Illuminate\Support\ServiceProvider;
use Laniakea\DataTables\DataTablesManager;
use Laniakea\DataTables\Interfaces\DataTablesManagerInterface;
use Laniakea\Forms\FormIdsGenerator;
use Laniakea\Forms\FormsManager;
use Laniakea\Forms\Interfaces\FormIdsGeneratorInterface;
use Laniakea\Forms\Interfaces\FormsManagerInterface;
use Laniakea\Resources\Interfaces\ResourceManagerInterface;
use Laniakea\Resources\Interfaces\ResourceRegistrarInterface;
use Laniakea\Resources\ResourceManager;
use Laniakea\Resources\ResourceManagerCommands;
use Laniakea\Resources\ResourceRouteBinder;
use Laniakea\Settings\Interfaces\SettingsGeneratorInterface;
use Laniakea\Settings\Interfaces\SettingsUpdaterInterface;
use Laniakea\Settings\Interfaces\SettingsValuesInterface;
use Laniakea\Settings\SettingsGenerator;
use Laniakea\Settings\SettingsUpdater;
use Laniakea\Settings\SettingsValues;
use Laniakea\Versions\Interfaces\VersionedResourceRegistrarInterface;
use Laniakea\Versions\VersionBinder;
use Laniakea\Versions\VersionedContainer;

class LaniakeaServiceProvider extends ServiceProvider
{
    /**
     * Boot services.
     *
     * @param VersionedContainer $versionedContainer
     */
    public function boot(VersionedContainer $versionedContainer): void
    {
        $this->publishes([
            __DIR__.'/../config/laniakea.php' => config_path('laniakea.php'),
        ]);

        $this->runRegistrars($versionedContainer);
    }

    /**
     * Register services.
     */
    public function register(): void
    {
        $this->mergeConfigFrom(__DIR__.'/../config/laniakea.php', 'laniakea');

        $this->registerResourceManagerCommands();
        $this->registerResourceManager();

        $container = $this->getFreshVersionedContainer();
        $this->app->instance(VersionedContainer::class, $container);

        $this->registerForms();
        $this->registerSettings();
        $this->registerDataTables();
    }

    protected function getFreshVersionedContainer(): VersionedContainer
    {
        return new VersionedContainer();
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
        $this->bindPackageAbstractions([
            ResourceManagerInterface::class => ResourceManager::class,
        ]);
    }

    protected function runRegistrars(VersionedContainer $container): void
    {
        $versionBinder = new VersionBinder($container);

        collect(config('laniakea.registrars', []))->each(function (string $name) use ($versionBinder) {
            /** @var ResourceRegistrarInterface $registrar */
            $registrar = $this->app->make($name);

            $registrar->bindRoute(new ResourceRouteBinder());

            if ($registrar instanceof VersionedResourceRegistrarInterface) {
                $registrar->bindVersions($versionBinder);
            }
        });
    }

    protected function registerForms(): void
    {
        $this->bindPackageAbstractions([
            FormIdsGeneratorInterface::class => FormIdsGenerator::class,
            FormsManagerInterface::class => FormsManager::class,
        ]);
    }

    protected function registerSettings(): void
    {
        $this->bindPackageAbstractions([
            SettingsGeneratorInterface::class => SettingsGenerator::class,
            SettingsUpdaterInterface::class => SettingsUpdater::class,
            SettingsValuesInterface::class => SettingsValues::class,
        ]);
    }

    protected function registerDataTables(): void
    {
        $this->bindPackageAbstractions([
            DataTablesManagerInterface::class => DataTablesManager::class,
        ]);
    }

    protected function getConfig(): Repository
    {
        return $this->app->make('config');
    }

    protected function bindPackageAbstractions(array $bindings): void
    {
        foreach ($bindings as $abstract => $concrete) {
            $this->app->bind($abstract, function () use ($abstract, $concrete) {
                return $this->app->make(
                    $this->getConfig()->get('laniakea.bindings.'.$abstract, $concrete),
                );
            });
        }
    }
}
