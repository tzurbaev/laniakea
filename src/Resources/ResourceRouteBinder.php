<?php

declare(strict_types=1);

namespace Laniakea\Resources;

use Illuminate\Container\Container;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Facades\Route;
use Laniakea\Repositories\Interfaces\RepositoryInterface;
use Laniakea\Resources\Interfaces\ResourceInterface;
use Laniakea\Resources\Interfaces\ResourceRequestInterface;
use Laniakea\Resources\Interfaces\ResourceRouteBinderInterface;

readonly class ResourceRouteBinder implements ResourceRouteBinderInterface
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
    ): void {
        Route::bind($name, function ($id) use ($resource, $repository, $notFoundException, $context) {
            $exception = $this->getException($notFoundException);
            $manager = Container::getInstance()->make(ResourceManager::class);

            try {
                return $manager->getItem(
                    $id,
                    Container::getInstance()->make(ResourceRequestInterface::class),
                    $this->getResource($resource),
                    $this->getRepository($repository),
                    $context,
                );
            } catch (ModelNotFoundException $e) {
                if (is_null($exception)) {
                    throw $e;
                }

                throw $exception;
            }
        });
    }

    /**
     * Get custom exception instance.
     *
     * @param string|\Throwable|null $e
     *
     * @return \Throwable|null
     */
    protected function getException(string|\Throwable|null $e): ?\Throwable
    {
        if (is_null($e)) {
            return null;
        }

        return $e instanceof \Throwable ? $e : new $e();
    }

    /**
     * Get resource instance.
     *
     * @param string|ResourceInterface $resource
     *
     * @throws \Illuminate\Contracts\Container\BindingResolutionException
     *
     * @return ResourceInterface
     */
    protected function getResource(string|ResourceInterface $resource): ResourceInterface
    {
        return $resource instanceof ResourceInterface ? $resource : Container::getInstance()->make($resource);
    }

    /**
     * Get repository instance.
     *
     * @param string|RepositoryInterface $repository
     *
     * @throws \Illuminate\Contracts\Container\BindingResolutionException
     *
     * @return RepositoryInterface
     */
    protected function getRepository(string|RepositoryInterface $repository): RepositoryInterface
    {
        return $repository instanceof RepositoryInterface ? $repository : Container::getInstance()->make($repository);
    }
}
