<?php

declare(strict_types=1);

namespace Laniakea\Resources\Commands;

use Laniakea\Repositories\Interfaces\RepositoryQueryBuilderInterface;
use Laniakea\Resources\Interfaces\HasDefaultInclusionsInterface;
use Laniakea\Resources\Interfaces\HasGlobalInclusionsInterface;
use Laniakea\Resources\Interfaces\ResourceContextInterface;
use Laniakea\Resources\Interfaces\ResourceInterface;
use Laniakea\Resources\Interfaces\ResourceManagerCommandInterface;
use Laniakea\Resources\Interfaces\ResourceRequestInterface;

readonly class LoadInclusions implements ResourceManagerCommandInterface
{
    /**
     * This command performs eager loading of requested relationships.
     *
     * @param RepositoryQueryBuilderInterface $query
     * @param ResourceContextInterface        $context
     */
    public function run(RepositoryQueryBuilderInterface $query, ResourceContextInterface $context): void
    {
        $query->beforeCriteria(function () use ($query, $context) {
            $requestedInclusions = $this->getRequestedInclusions($context->getRequest(), $context->getResource());

            if (!count($requestedInclusions)) {
                return;
            }

            $relations = collect($context->getResource()->getInclusions())
                ->only($requestedInclusions)
                ->flatten()
                ->unique()
                ->values()
                ->all();

            if (!count($relations)) {
                return;
            }

            $query->with($relations);
        });
    }

    protected function getRequestedInclusions(ResourceRequestInterface $request, ResourceInterface $resource): array
    {
        $global = $resource instanceof HasGlobalInclusionsInterface ? $resource->getGlobalInclusions() : [];
        $inclusions = $request->getInclusions();

        if (!count($inclusions)) {
            $inclusions = $resource instanceof HasDefaultInclusionsInterface ? $resource->getDefaultInclusions() : [];
        }

        return collect([...$global, ...$inclusions])->unique()->values()->all();
    }
}
