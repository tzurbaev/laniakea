<?php

declare(strict_types=1);

namespace Laniakea\DataTables\Interfaces;

interface DataTableInterface
{
    public function getMethod(): string;

    public function getUrl(): string;

    public function getHeaders(): array;

    public function getDataPath(): ?string;

    /** @return array|DataTableColumnInterface[] */
    public function getColumns(): array;

    /** @return array|DataTableFilterInterface[] */
    public function getFilters(): array;
}
