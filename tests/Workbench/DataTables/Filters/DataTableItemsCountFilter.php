<?php

declare(strict_types=1);

namespace Laniakea\Tests\Workbench\DataTables\Filters;

use Illuminate\Http\Request;
use Laniakea\DataTables\Interfaces\DataTableFilterInterface;

class DataTableItemsCountFilter implements DataTableFilterInterface
{
    public function getType(): string
    {
        return 'SelectField';
    }

    public function getFieldName(): string
    {
        return 'count';
    }

    public function getLabel(): ?string
    {
        return 'Items Count';
    }

    public function getCurrentValue(Request $request): int
    {
        return $request->filled('count') ? intval($request->input('count')) : 15;
    }

    public function getSettings(): array
    {
        return [];
    }
}
