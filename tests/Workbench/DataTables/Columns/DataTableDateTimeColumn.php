<?php

declare(strict_types=1);

namespace Laniakea\Tests\Workbench\DataTables\Columns;

use Laniakea\DataTables\DataTableColumnSorting;
use Laniakea\DataTables\Enums\DataTableSortingType;
use Laniakea\DataTables\Interfaces\DataTableColumnInterface;
use Laniakea\DataTables\Interfaces\DataTableColumnSortingInterface;

readonly class DataTableDateTimeColumn implements DataTableColumnInterface
{
    public function __construct(
        private string $column,
        private string $label = 'Created At',
        private string $format = 'yyyy-MM-dd HH:mm:ss',
    ) {
        //
    }

    public function getType(): string
    {
        return 'DateTimeColumn';
    }

    public function getLabel(): ?string
    {
        return $this->label;
    }

    public function getSorting(): ?DataTableColumnSortingInterface
    {
        return new DataTableColumnSorting($this->column, DataTableSortingType::NUMERICAL);
    }

    public function getSettings(): array
    {
        return [
            'column' => $this->column,
            'format' => $this->format,
        ];
    }
}
