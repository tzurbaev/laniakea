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

    /**
     * Get current resource request instance.
     *
     * @return ResourceRequestInterface
     */
    public function getRequest(): ResourceRequestInterface
    {
        return $this->request;
    }

    /**
     * Get resource instance.
     *
     * @return ResourceInterface
     */
    public function getResource(): ResourceInterface
    {
        return $this->resource;
    }

    /**
     * Get repository instance.
     *
     * @return RepositoryInterface
     */
    public function getRepository(): RepositoryInterface
    {
        return $this->repository;
    }

    /**
     * Set custom context data.
     *
     * @param string $key
     * @param mixed  $value
     *
     * @return $this
     */
    public function setContext(string $key, mixed $value): static
    {
        $this->context[$key] = $value;

        return $this;
    }

    /**
     * Get custom context data.
     *
     * @param string|null $key
     *
     * @return mixed
     */
    public function getContext(?string $key = null): mixed
    {
        if (is_null($key)) {
            return $this->context;
        }

        return $this->context[$key] ?? null;
    }
}
