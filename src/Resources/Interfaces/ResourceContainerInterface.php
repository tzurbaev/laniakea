<?php

declare(strict_types=1);

namespace Laniakea\Resources\Interfaces;

use Laniakea\Repositories\Interfaces\RepositoryInterface;

interface ResourceContainerInterface
{
    /**
     * Get resource instance.
     *
     * @return ResourceInterface
     */
    public function getResource(): ResourceInterface;

    /**
     * Get repository instance.
     *
     * @return RepositoryInterface
     */
    public function getRepository(): RepositoryInterface;
}
