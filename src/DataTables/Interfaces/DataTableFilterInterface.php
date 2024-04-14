<?php

declare(strict_types=1);

namespace Laniakea\DataTables\Interfaces;

use Illuminate\Http\Request;

interface DataTableFilterInterface
{
    public function getType(): string;

    public function getFieldName(): string;

    public function getLabel(): ?string;

    public function getCurrentValue(Request $request): mixed;

    public function getSettings(): array;
}
