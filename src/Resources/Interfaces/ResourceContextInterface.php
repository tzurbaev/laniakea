<?php

declare(strict_types=1);

namespace Laniakea\Resources\Interfaces;

use Laniakea\Repositories\Interfaces\RepositoryInterface;

interface ResourceContextInterface
{
    public function getRequest(): ResourceRequestInterface;

    public function getResource(): ResourceInterface;

    public function getRepository(): RepositoryInterface;

    public function setContext(string $key, mixed $value): static;

    public function getContext(?string $key = null): mixed;
}
