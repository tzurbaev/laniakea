<?php

declare(strict_types=1);

namespace Laniakea\Resources;

use Laniakea\Resources\Interfaces\ResourceContextInterface;

class ContextualResourceEntity
{
    /**
     * Current resource contenxt.
     *
     * @var ResourceContextInterface|null
     */
    protected ?ResourceContextInterface $context = null;

    /**
     * Set resource context to the current resource entity.
     *
     * @param ResourceContextInterface $context
     *
     * @return $this
     */
    public function setResourceContext(ResourceContextInterface $context): static
    {
        $this->context = $context;

        return $this;
    }

    /**
     * Get previously bound resource context.
     *
     * @return ResourceContextInterface|null
     */
    public function getResourceContext(): ?ResourceContextInterface
    {
        return $this->context;
    }
}
