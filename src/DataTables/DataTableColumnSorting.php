<?php

declare(strict_types=1);

namespace Laniakea\DataTables;

use Laniakea\DataTables\Enums\DataTableSortingType;
use Laniakea\DataTables\Interfaces\DataTableColumnSortingInterface;

readonly class DataTableColumnSorting implements DataTableColumnSortingInterface
{
    public function __construct(private string $column, private DataTableSortingType $type = DataTableSortingType::ALPHABETICAL)
    {
        //
    }

    public function getColumn(): string
    {
        return $this->column;
    }

    public function getType(): DataTableSortingType
    {
        return $this->type;
    }
}
