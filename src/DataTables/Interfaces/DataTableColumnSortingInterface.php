<?php

declare(strict_types=1);

namespace Laniakea\DataTables\Interfaces;

use Laniakea\DataTables\Enums\DataTableSortingType;

interface DataTableColumnSortingInterface
{
    public function getType(): DataTableSortingType;

    public function getColumn(): string;
}
