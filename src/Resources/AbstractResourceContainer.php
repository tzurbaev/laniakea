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

    public function getResource(): ResourceInterface
    {
        return $this->resourceInstance = $this->getInstance($this->resourceInstance, $this->resource);
    }

    public function getRepository(): RepositoryInterface
    {
        return $this->repositoryInstance = $this->getInstance($this->repositoryInstance, $this->repository);
    }

    public function getTransformer()
    {
        return $this->transformerInstance = $this->getInstance($this->transformerInstance, $this->transformer);
    }

    protected function getInstance(mixed $currentInstance, string $className)
    {
        if (!is_null($currentInstance)) {
            return $currentInstance;
        }

        return Container::getInstance()->make($className);
    }
}
