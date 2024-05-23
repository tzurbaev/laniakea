<?php

declare(strict_types=1);

namespace Laniakea\DataTables\Interfaces;

interface DataTableColumnInterface
{
    /**
     * Datatable column type.
     *
     * @return string
     */
    public function getType(): string;

    /**
     * Datatable column label.
     *
     * @return string|null
     */
    public function getLabel(): ?string;

    /**
     * Datatable column sorting definition.
     *
     * @return DataTableColumnSortingInterface|null
     */
    public function getSorting(): ?DataTableColumnSortingInterface;

    /**
     * Additional settings for datatable column.
     *
     * @return array
     */
    public function getSettings(): array;
}
