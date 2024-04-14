<?php

declare(strict_types=1);

namespace Laniakea\Tests\Workbench\DataTables;

use Laniakea\Shared\Interfaces\HasDefaultSortingInterface;

class ArticlesDataTableWithDefaultSorting extends ArticlesDataTable implements HasDefaultSortingInterface
{
    public function getDefaultSortingColumn(): string
    {
        return 'created_at';
    }

    public function getDefaultSortingDirection(): string
    {
        return 'desc';
    }
}
