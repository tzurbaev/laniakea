<?php

declare(strict_types=1);

namespace Laniakea\Resources\Interfaces;

interface HasResourceContextInterface
{
    /**
     * Set resource context to the current resource entity.
     *
     * @param ResourceContextInterface $context
     *
     * @return $this
     */
    public function setResourceContext(ResourceContextInterface $context): static;

    /**
     * Get previously bound resource context.
     *
     * @return ResourceContextInterface|null
     */
    public function getResourceContext(): ?ResourceContextInterface;
}
