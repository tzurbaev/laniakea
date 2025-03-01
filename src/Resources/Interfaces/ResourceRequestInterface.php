<?php

declare(strict_types=1);

namespace Laniakea\Resources\Interfaces;

interface ResourceRequestInterface
{
    /**
     * Get pagination limit.
     *
     * @return int|null
     */
    public function getCount(): ?int;

    /**
     * Get current page for pagination.
     *
     * @return int|null
     */
    public function getPage(): ?int;

    /**
     * Get list of requested inclusions.
     *
     * @return array<string>
     */
    public function getInclusions(): array;

    /**
     * Get filter values (limited to provided keys).
     *
     * @param array $filters
     *
     * @return array<string, mixed>
     */
    public function getFilters(array $filters): array;

    /**
     * Get sorting column.
     *
     * @return string|null
     */
    public function getSortingColumn(): ?string;

    /**
     * Get sorting direction (asc/desc).
     *
     * @return string|null
     */
    public function getSortingDirection(): ?string;
}
