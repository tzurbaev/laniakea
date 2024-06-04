<?php

declare(strict_types=1);

namespace Laniakea\DataTables;

use Laniakea\DataTables\Interfaces\DataTableInterface;

abstract class AbstractDataTable implements DataTableInterface
{
    public function getId(): mixed
    {
        return null;
    }

    public function getMethod(): string
    {
        return 'GET';
    }

    public function getHeaders(): array
    {
        return [
            'Accept' => 'application/json',
        ];
    }

    public function getFilters(): array
    {
        return [];
    }

    public function getDataPath(): ?string
    {
        return null;
    }

    public function getViews(): array
    {
        return [];
    }

    public function getSettings(): array
    {
        return [];
    }
}
