<?php

declare(strict_types=1);

namespace Laniakea\Resources;

use Laniakea\Repositories\Interfaces\RepositoryInterface;
use Laniakea\Resources\Interfaces\ResourceContextInterface;
use Laniakea\Resources\Interfaces\ResourceInterface;
use Laniakea\Resources\Interfaces\ResourceRequestInterface;

class ResourceContext implements ResourceContextInterface
{
    public function __construct(
        protected readonly ResourceRequestInterface $request,
        protected readonly ResourceInterface $resource,
        protected readonly RepositoryInterface $repository,
        protected array $context = [],
    ) {
        //
    }

    public function getRequest(): ResourceRequestInterface
    {
        return $this->request;
    }

    public function getResource(): ResourceInterface
    {
        return $this->resource;
    }

    public function getRepository(): RepositoryInterface
    {
        return $this->repository;
    }

    public function setContext(string $key, mixed $value): static
    {
        $this->context[$key] = $value;

        return $this;
    }

    public function getContext(?string $key = null): mixed
    {
        if (is_null($key)) {
            return $this->context;
        }

        return $this->context[$key] ?? null;
    }
}
