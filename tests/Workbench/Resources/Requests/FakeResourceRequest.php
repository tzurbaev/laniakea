<?php

declare(strict_types=1);

namespace Laniakea\Tests\Workbench\Resources\Requests;

use Laniakea\Resources\Interfaces\ResourceRequestInterface;

readonly class FakeResourceRequest implements ResourceRequestInterface
{
    public function __construct(
        private int $page = 1,
        private int $count = 10,
        private array $inclusions = [],
        private array $filters = [],
        private ?string $sortingColumn = null,
        private ?string $sortingDirection = null,
    ) {
        //
    }

    public function getPage(): int
    {
        return $this->page;
    }

    public function getCount(): int
    {
        return $this->count;
    }

    public function getInclusions(): array
    {
        return $this->inclusions;
    }

    public function getFilters(array $filters): array
    {
        return $this->filters;
    }

    public function getSortingColumn(): ?string
    {
        return $this->sortingColumn;
    }

    public function getSortingDirection(): ?string
    {
        return $this->sortingDirection;
    }
}
