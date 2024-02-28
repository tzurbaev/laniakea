<?php

declare(strict_types=1);

namespace Laniakea\Tests;

use Laniakea\Resources\ResourceManager;

trait CreatesResourceManager
{
    public function getResourceManager(): ResourceManager
    {
        return app(ResourceManager::class);
    }
}
