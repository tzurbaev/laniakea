<?php

declare(strict_types=1);

namespace Laniakea\Transformers\Resources;

use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Laniakea\Transformers\Interfaces\TransformerResourceInterface;

class CollectionResource implements TransformerResourceInterface
{
    public function __construct(
        protected readonly iterable $data,
        protected readonly mixed $transformer,
        protected ?LengthAwarePaginator $paginator = null,
    ) {
        //
    }

    public function getData(): iterable
    {
        return $this->data;
    }

    public function getTransformer(): mixed
    {
        return $this->transformer;
    }

    /**
     * Get collection paginator.
     *
     * @return LengthAwarePaginator|null
     */
    public function getPaginator(): ?LengthAwarePaginator
    {
        return $this->paginator;
    }

    /**
     * Set collection paginator.
     *
     * @param LengthAwarePaginator $paginator
     *
     * @return $this
     */
    public function setPaginator(LengthAwarePaginator $paginator): static
    {
        $this->paginator = $paginator;

        return $this;
    }

    /**
     * Check if collection has paginator.
     *
     * @return bool
     */
    public function hasPaginator(): bool
    {
        return !is_null($this->paginator);
    }
}
