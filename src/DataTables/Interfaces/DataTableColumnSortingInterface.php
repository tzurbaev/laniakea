<?php

declare(strict_types=1);

namespace Laniakea\DataTables\Interfaces;

use Laniakea\DataTables\Enums\DataTableSortingType;

interface DataTableColumnSortingInterface
{
    /**
     * Sorting type.
     *
     * @return DataTableSortingType
     */
    public function getType(): DataTableSortingType;

    /**
     * Sorting column.
     *
     * @return string
     */
    public function getColumn(): string;
}
