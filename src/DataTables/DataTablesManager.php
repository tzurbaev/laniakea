<?php

declare(strict_types=1);

namespace Laniakea\DataTables;

use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Laniakea\DataTables\Interfaces\DataTableColumnInterface;
use Laniakea\DataTables\Interfaces\DataTableFilterInterface;
use Laniakea\DataTables\Interfaces\DataTableInterface;
use Laniakea\DataTables\Interfaces\DataTablesManagerInterface;
use Laniakea\Shared\Interfaces\HasDefaultSortingInterface;

readonly class DataTablesManager implements DataTablesManagerInterface
{
    public function getDataTableData(Request $request, DataTableInterface $dataTable): array
    {
        return [
            'api' => $this->getApi($dataTable),
            'columns' => $this->getColumns($dataTable),
            'filters' => $this->getFilters($request, $dataTable),
            'sorting' => $this->getCurrentSorting($request, $dataTable),
        ];
    }

    protected function getApi(DataTableInterface $dataTable): array
    {
        return [
            'method' => $dataTable->getMethod(),
            'url' => $dataTable->getUrl(),
            'headers' => $dataTable->getHeaders(),
            'data_path' => $dataTable->getDataPath(),
        ];
    }

    protected function getColumns(DataTableInterface $dataTable): array
    {
        return array_map(function (DataTableColumnInterface $column) {
            $sorting = $column->getSorting();

            return [
                'type' => $column->getType(),
                'label' => $column->getLabel(),
                'sorting' => !is_null($sorting) ? [
                    'column' => $sorting->getColumn(),
                    'type' => $sorting->getType()->value,
                ] : null,
                'settings' => $column->getSettings(),
            ];
        }, $dataTable->getColumns());
    }

    protected function getFilters(Request $request, DataTableInterface $dataTable): array
    {
        return array_map(fn (DataTableFilterInterface $filter) => [
            'type' => $filter->getType(),
            'field_name' => $filter->getFieldName(),
            'label' => $filter->getLabel(),
            'value' => $filter->getCurrentValue($request),
            'settings' => $filter->getSettings(),
        ], $dataTable->getFilters());
    }

    protected function getCurrentSorting(Request $request, DataTableInterface $dataTable): ?array
    {
        $sorting = $request->input(config('laniakea.datatables.fields.sorting', 'order_by'));

        if (is_null($sorting)) {
            return $this->getDefaultSorting($dataTable);
        }

        $isDescending = Str::startsWith($sorting, '-');

        return [
            'column' => $isDescending ? Str::substr($sorting, 1) : $sorting,
            'direction' => $isDescending ? 'desc' : 'asc',
        ];
    }

    protected function getDefaultSorting(DataTableInterface $dataTable): ?array
    {
        if (!($dataTable instanceof HasDefaultSortingInterface)) {
            return null;
        }

        return [
            'column' => $dataTable->getDefaultSortingColumn(),
            'direction' => $dataTable->getDefaultSortingDirection(),
        ];
    }
}
