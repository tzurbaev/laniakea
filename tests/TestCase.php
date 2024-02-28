<?php

declare(strict_types=1);

namespace Laniakea\Tests;

use Illuminate\Contracts\Debug\ExceptionHandler;
use Laniakea\LaniakeaServiceProvider;
use Laniakea\Tests\Workbench\Exceptions\FakeExceptionHandler;
use Laniakea\Tests\Workbench\TestingServiceProvider;
use Laniakea\Tests\Workbench\Versions\ArticlesRegistrar;
use Orchestra\Testbench\TestCase as BaseTestCase;

class TestCase extends BaseTestCase
{
    protected function getEnvironmentSetUp($app): void
    {
        config()->set('database.connections.testing', [
            'driver' => 'sqlite',
            'database' => ':memory:',
            'prefix' => '',
        ]);

        config()->set('database.default', 'testing');
        config()->set('laniakea.registrars', [ArticlesRegistrar::class]);
    }

    protected function resolveApplicationExceptionHandler($app): void
    {
        $app->singleton(ExceptionHandler::class, FakeExceptionHandler::class);
    }

    protected function getPackageProviders($app): array
    {
        return [
            LaniakeaServiceProvider::class,
            TestingServiceProvider::class,
        ];
    }

    protected function defineRoutes($router): void
    {
        $router->group([], __DIR__.'/Workbench/routes/routes.php');
    }
}
