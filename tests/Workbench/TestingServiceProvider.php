<?php

declare(strict_types=1);

namespace Laniakea\Tests\Workbench;

use Illuminate\Support\ServiceProvider;

class TestingServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        $this->loadTranslationsFrom(__DIR__.'/lang', 'testing');
        $this->loadViewsFrom(__DIR__.'/views', 'testing');
        $this->loadMigrationsFrom(__DIR__.'/migrations');
    }
}
