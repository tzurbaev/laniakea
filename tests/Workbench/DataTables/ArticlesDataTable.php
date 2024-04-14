<?php

declare(strict_types=1);

namespace Laniakea\Tests\Workbench\DataTables;

use Laniakea\DataTables\AbstractDataTable;
use Laniakea\DataTables\DataTableColumn;
use Laniakea\DataTables\DataTableColumnSorting;
use Laniakea\DataTables\Enums\DataTableSortingType;
use Laniakea\Tests\Workbench\DataTables\Columns\ArticleTitleColumn;
use Laniakea\Tests\Workbench\DataTables\Columns\DataTableDateTimeColumn;
use Laniakea\Tests\Workbench\DataTables\Filters\DataTableItemsCountFilter;

class ArticlesDataTable extends AbstractDataTable
{
    public function getUrl(): string
    {
        return '/articles';
    }

    public function getMethod(): string
    {
        return 'POST';
    }

    public function getHeaders(): array
    {
        return [
            'Accept' => 'application/json',
            'X-Custom' => 'custom',
        ];
    }

    public function getDataPath(): ?string
    {
        return 'data.items';
    }

    public function getColumns(): array
    {
        return [
            new DataTableColumn(
                type: 'IDColumn',
                label: 'ID',
                sorting: new DataTableColumnSorting('id', DataTableSortingType::NUMERICAL),
                settings: ['show_copy_button' => true],
            ),
            new ArticleTitleColumn(),
            new DataTableDateTimeColumn(
                column: 'created_at',
                label: 'Published At',
                format: "LLLL do, yyyy 'at' HH:mm",
            ),
        ];
    }

    public function getFilters(): array
    {
        return [
            new DataTableItemsCountFilter(),
        ];
    }
}
