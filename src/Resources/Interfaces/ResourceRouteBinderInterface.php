<?php

declare(strict_types=1);

namespace Laniakea\Resources\Interfaces;

use Laniakea\Repositories\Interfaces\RepositoryInterface;

interface ResourceRouteBinderInterface
{
    /**
     * Create route model binding for the given resource.
     *
     * @param string                     $name
     * @param string|ResourceInterface   $resource
     * @param string|RepositoryInterface $repository
     * @param string|\Throwable|null     $notFoundException
     * @param array                      $context
     */
    public function bind(
        string $name,
        string|ResourceInterface $resource,
        string|RepositoryInterface $repository,
        string|\Throwable|null $notFoundException = null,
        array $context = [],
    ): void;
}
