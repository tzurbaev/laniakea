<?php

declare(strict_types=1);

namespace Laniakea\DataTables\Enums;

enum DataTableSortingType: string
{
    case ALPHABETICAL = 'alphabetical';
    case NUMERICAL = 'numerical';
}
