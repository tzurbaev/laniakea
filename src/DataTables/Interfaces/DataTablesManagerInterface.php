<?php

declare(strict_types=1);

namespace Laniakea\DataTables\Interfaces;

use Illuminate\Http\Request;

interface DataTablesManagerInterface
{
    public function getDataTableData(Request $request, DataTableInterface $dataTable): array;
}
