<?php

declare(strict_types=1);

namespace Laniakea\Resources\Interfaces;

use Laniakea\Repositories\Interfaces\RepositoryInterface;

interface ResourceContainerInterface
{
    public function getResource(): ResourceInterface;

    public function getRepository(): RepositoryInterface;
}
