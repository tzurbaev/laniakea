<?php

declare(strict_types=1);

namespace Laniakea\Resources\Commands;

use Laniakea\Repositories\Interfaces\RepositoryQueryBuilderInterface;
use Laniakea\Resources\Interfaces\HasResourceContextInterface;
use Laniakea\Resources\Interfaces\ResourceContextInterface;
use Laniakea\Resources\Interfaces\ResourceInterface;
use Laniakea\Resources\Interfaces\ResourceManagerCommandInterface;
use Laniakea\Resources\Interfaces\ResourceRequestInterface;
use Laniakea\Resources\Interfaces\ResourceSorterInterface;
use Laniakea\Shared\Interfaces\HasDefaultSortingInterface;

readonly class SortResources implements ResourceManagerCommandInterface
{
    /**
     * This command applies resource sorting to the current query.
     *
     * @param RepositoryQueryBuilderInterface $query
     * @param ResourceContextInterface        $context
     */
    public function run(RepositoryQueryBuilderInterface $query, ResourceContextInterface $context): void
    {
        $query->afterCriteria(function () use ($query, $context) {
            [$column, $direction] = $this->getSorting($context->getRequest(), $context->getResource());

            if (is_null($column)) {
                return;
            }

            /** @var ResourceSorterInterface|null $sorter */
            $sorter = $context->getResource()->getSorters()[$column] ?? null;

            if (is_null($sorter)) {
                return;
            }

            if ($sorter instanceof HasResourceContextInterface) {
                $sorter->setResourceContext($context);
            }

            $sorter->sort($query, $column, $direction ?? 'asc');
        });
    }

    protected function getSorting(ResourceRequestInterface $request, ResourceInterface $resource): ?array
    {
        $column = $request->getSortingColumn();
        $direction = $request->getSortingDirection();

        if (!is_null($column)) {
            return [$column, $direction];
        } elseif ($resource instanceof HasDefaultSortingInterface) {
            return [
                $resource->getDefaultSortingColumn(),
                $resource->getDefaultSortingDirection(),
            ];
        }

        return null;
    }
}
