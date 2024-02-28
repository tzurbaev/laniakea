<?php

declare(strict_types=1);

namespace Laniakea\Resources\Interfaces;

interface HasResourceContextInterface
{
    public function setResourceContext(ResourceContextInterface $context): static;

    public function getResourceContext(): ?ResourceContextInterface;
}
