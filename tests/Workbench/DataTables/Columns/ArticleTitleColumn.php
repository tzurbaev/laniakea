<?php

declare(strict_types=1);

namespace Laniakea\Tests\Workbench\DataTables\Columns;

use Laniakea\DataTables\DataTableColumnSorting;
use Laniakea\DataTables\Enums\DataTableSortingType;
use Laniakea\DataTables\Interfaces\DataTableColumnInterface;
use Laniakea\DataTables\Interfaces\DataTableColumnSortingInterface;

class ArticleTitleColumn implements DataTableColumnInterface
{
    public function getType(): string
    {
        return 'ArticleTitleColumn';
    }

    public function getLabel(): ?string
    {
        return 'Article';
    }

    public function getSorting(): ?DataTableColumnSortingInterface
    {
        return new DataTableColumnSorting('title', DataTableSortingType::ALPHABETICAL);
    }

    public function getSettings(): array
    {
        return ['preview_length' => 250];
    }
}
