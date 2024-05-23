<?php

declare(strict_types=1);

namespace Laniakea\DataTables\Interfaces;

use Illuminate\Http\Request;

interface DataTablesManagerInterface
{
    /**
     * Returns datatable data to be used in frontend.
     *
     * @param Request            $request
     * @param DataTableInterface $dataTable
     *
     * @return array
     */
    public function getDataTableData(Request $request, DataTableInterface $dataTable): array;
}
