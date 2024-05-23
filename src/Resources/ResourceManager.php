<?php

declare(strict_types=1);

namespace Laniakea\Resources;

use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;
use Laniakea\Repositories\Criteria\ExactValueCriterion;
use Laniakea\Repositories\Interfaces\RepositoryInterface;
use Laniakea\Repositories\Interfaces\RepositoryQueryBuilderInterface;
use Laniakea\Resources\Interfaces\HasItemCriterionInterface;
use Laniakea\Resources\Interfaces\ResourceContextInterface;
use Laniakea\Resources\Interfaces\ResourceInterface;
use Laniakea\Resources\Interfaces\ResourceManagerCommandInterface;
use Laniakea\Resources\Interfaces\ResourceManagerInterface;
use Laniakea\Resources\Interfaces\ResourceRequestInterface;

readonly class ResourceManager implements ResourceManagerInterface
{
    public function __construct(private ResourceManagerCommands $commands)
    {
        //
    }

    public function getPaginator(
        ResourceRequestInterface $request,
        ResourceInterface $resource,
        RepositoryInterface $repository,
        ?callable $callback = null,
        array $context = [],
    ): LengthAwarePaginator {
        return $repository->paginate(
            $request->getPage(),
            $request->getCount(),
            $this->getRepositoryCallback(
                new ResourceContext($request, $resource, $repository, $context),
                $this->commands->getPaginationCommands(),
                $callback,
            ),
        );
    }

    public function getList(
        ResourceRequestInterface $request,
        ResourceInterface $resource,
        RepositoryInterface $repository,
        ?callable $callback = null,
        array $context = [],
    ): Collection {
        return $repository->list(
            $this->getRepositoryCallback(
                new ResourceContext($request, $resource, $repository, $context),
                $this->commands->getListCommands(),
                $callback,
            ),
        );
    }

    public function getItem(
        mixed $id,
        ResourceRequestInterface $request,
        ResourceInterface $resource,
        RepositoryInterface $repository,
        array $context = [],
    ): Model {
        $criterion = $resource instanceof HasItemCriterionInterface
            ? $resource->getItemCriterion($id)
            : new ExactValueCriterion('id', $id);

        return $repository->firstOrFail(
            $this->getRepositoryCallback(
                new ResourceContext($request, $resource, $repository, $context),
                $this->commands->getItemCommands(),
                fn (RepositoryQueryBuilderInterface $builder) => $builder->addCriteria([$criterion]),
            )
        );
    }

    /**
     * Creates repository callback with required resource manager commands.
     *
     * @param ResourceContextInterface          $context
     * @param ResourceManagerCommandInterface[] $commands
     * @param callable|null                     $callback
     *
     * @return callable
     */
    protected function getRepositoryCallback(
        ResourceContextInterface $context,
        array $commands,
        ?callable $callback = null,
    ): callable {
        return function (RepositoryQueryBuilderInterface $builder) use ($context, $commands, $callback) {
            if (is_callable($callback)) {
                call_user_func_array($callback, [$builder, $context]);
            }

            foreach ($commands as $command) {
                $command->run($builder, $context);
            }
        };
    }
}
