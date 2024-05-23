<?php

declare(strict_types=1);

namespace Laniakea\Resources\Interfaces;

use Laniakea\Repositories\Interfaces\RepositoryInterface;

interface ResourceContextInterface
{
    /**
     * Get current resource request instance.
     *
     * @return ResourceRequestInterface
     */
    public function getRequest(): ResourceRequestInterface;

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

    /**
     * Set custom context data.
     *
     * @param string $key
     * @param mixed  $value
     *
     * @return $this
     */
    public function setContext(string $key, mixed $value): static;

    /**
     * Get custom context data.
     *
     * @param string|null $key
     *
     * @return mixed
     */
    public function getContext(?string $key = null): mixed;
}
