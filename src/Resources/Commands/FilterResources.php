<?php

declare(strict_types=1);

namespace Laniakea\Resources\Commands;

use Illuminate\Container\Container;
use Illuminate\Support\Arr;
use Laniakea\Repositories\Interfaces\RepositoryQueryBuilderInterface;
use Laniakea\Resources\Interfaces\HasDefaultFilterValuesInterface;
use Laniakea\Resources\Interfaces\HasResourceContextInterface;
use Laniakea\Resources\Interfaces\ResourceContextInterface;
use Laniakea\Resources\Interfaces\ResourceFilterInterface;
use Laniakea\Resources\Interfaces\ResourceInterface;
use Laniakea\Resources\Interfaces\ResourceManagerCommandInterface;
use Laniakea\Resources\Interfaces\ResourceRequestInterface;

readonly class FilterResources implements ResourceManagerCommandInterface
{
    /**
     * This command applies resource filters to the current query.
     *
     * @param RepositoryQueryBuilderInterface $query
     * @param ResourceContextInterface        $context
     */
    public function run(RepositoryQueryBuilderInterface $query, ResourceContextInterface $context): void
    {
        $query->beforeCriteria(function () use ($query, $context) {
            // Only requested filters and filters with default values will be applied.
            $values = $this->getFilterValues(
                $context->getRequest(),
                $context->getResource(),
            );

            $filters = collect(Arr::only(
                $context->getResource()->getFilters(),
                array_keys($values)
            ));

            $container = Container::getInstance();

            $filters->each(function (ResourceFilterInterface|string $handler, string $name) use ($context, $container, $query, $values) {
                $filter = $handler instanceof ResourceFilterInterface ? $handler : $container->make($handler);

                if ($filter instanceof HasResourceContextInterface) {
                    $filter->setResourceContext($context);
                }

                $filter->apply($query, $values[$name], $values);
            });
        });
    }

    /**
     * Get requested and default filter values for the resource.
     *
     * @param ResourceRequestInterface $request
     * @param ResourceInterface        $resource
     *
     * @return array
     */
    protected function getFilterValues(ResourceRequestInterface $request, ResourceInterface $resource): array
    {
        $availableFilters = $resource->getFilters();
        $defaults = $resource instanceof HasDefaultFilterValuesInterface ? $resource->getDefaultFilterValues() : [];

        return [
            ...$defaults,
            ...$request->getFilters(array_keys($availableFilters)),
        ];
    }
}
