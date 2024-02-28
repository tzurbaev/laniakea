<?php

declare(strict_types=1);

namespace Laniakea\Resources;

use Laniakea\Resources\Interfaces\ResourceContextInterface;

class ContextualResourceEntity
{
    protected ?ResourceContextInterface $context = null;

    public function setResourceContext(ResourceContextInterface $context): static
    {
        $this->context = $context;

        return $this;
    }

    public function getResourceContext(): ?ResourceContextInterface
    {
        return $this->context;
    }
}
