<?php

declare(strict_types=1);

namespace Laniakea\DataTables\Interfaces;

interface DataTableColumnInterface
{
    public function getType(): string;

    public function getLabel(): ?string;

    public function getSorting(): ?DataTableColumnSortingInterface;

    public function getSettings(): array;
}
