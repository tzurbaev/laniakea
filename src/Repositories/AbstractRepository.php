<?php

declare(strict_types=1);

namespace Laniakea\Repositories;

use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;
use Laniakea\Repositories\Interfaces\RepositoryInterface;

abstract class AbstractRepository implements RepositoryInterface
{
    abstract protected function getModel(): string;

    protected function getFreshModel(): Model
    {
        $className = $this->getModel();

        return new $className();
    }

    protected function getFreshQuery(): Builder
    {
        return $this->getFreshModel()->newQuery();
    }

    protected function getQueryBuilder(?callable $callback): Builder
    {
        $queryBuilder = new RepositoryQueryBuilder($this->getFreshQuery());

        if ($callback) {
            $callback($queryBuilder);
        }

        foreach ($queryBuilder->getCriteria() as $criterion) {
            $criterion->apply($queryBuilder->getQueryBuilder());
        }

        return $queryBuilder->getQueryBuilder();
    }

    public function create(array $attributes): Model
    {
        $model = $this->getFreshModel();
        $model->fill($attributes);
        $model->save();

        return $model;
    }

    public function update(mixed $id, array $attributes): Model
    {
        return tap($this->findOrFail($id), function (Model $model) use ($attributes) {
            $model->update($attributes);
        });
    }

    public function delete(mixed $id): void
    {
        $this->findOrFail($id)->delete();
    }

    public function find(mixed $id, ?callable $callback = null): ?Model
    {
        return $this->getQueryBuilder($callback)->find($id);
    }

    public function findOrFail(mixed $id, ?callable $callback = null): Model
    {
        return $this->getQueryBuilder($callback)->findOrFail($id);
    }

    public function first(?callable $callback = null): ?Model
    {
        return $this->getQueryBuilder($callback)->first();
    }

    public function firstOrFail(?callable $callback = null): Model
    {
        return $this->getQueryBuilder($callback)->firstOrFail();
    }

    public function list(?callable $callback = null): Collection
    {
        return $this->getQueryBuilder($callback)->get();
    }

    public function paginate(?int $page, ?int $count, ?callable $callback = null): LengthAwarePaginator
    {
        return $this->getQueryBuilder($callback)->paginate(perPage: $count, page: $page);
    }
}
