<?php

declare(strict_types=1);

namespace Laniakea\Resources;

use Illuminate\Container\Container;
use Laniakea\Repositories\Interfaces\RepositoryInterface;
use Laniakea\Resources\Interfaces\ResourceContainerInterface;
use Laniakea\Resources\Interfaces\ResourceInterface;

abstract class AbstractResourceContainer implements ResourceContainerInterface
{
    private ?ResourceInterface $resourceInstance = null;
    private ?RepositoryInterface $repositoryInstance = null;
    private mixed $transformerInstance = null;

    protected string $resource;
    protected string $repository;
    protected string $transformer;

    /**
     * Get resource instance.
     *
     * @return ResourceInterface
     */
    public function getResource(): ResourceInterface
    {
        return $this->resourceInstance = $this->getInstance($this->resourceInstance, $this->resource);
    }

    /**
     * Get respository instance.
     *
     * @return RepositoryInterface
     */
    public function getRepository(): RepositoryInterface
    {
        return $this->repositoryInstance = $this->getInstance($this->repositoryInstance, $this->repository);
    }

    /**
     * Get resource transformer.
     *
     * @return mixed
     */
    public function getTransformer(): mixed
    {
        return $this->transformerInstance = $this->getInstance($this->transformerInstance, $this->transformer);
    }

    /**
     * Get either the current instance or create a new one.
     *
     * @param mixed  $currentInstance
     * @param string $className
     *
     * @throws \Illuminate\Contracts\Container\BindingResolutionException
     *
     * @return \Closure|mixed|object|null
     */
    protected function getInstance(mixed $currentInstance, string $className): mixed
    {
        if (!is_null($currentInstance)) {
            return $currentInstance;
        }

        return Container::getInstance()->make($className);
    }
}
