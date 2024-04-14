<?php

declare(strict_types=1);

namespace Laniakea\DataTables;

use Laniakea\DataTables\Interfaces\DataTableInterface;

abstract class AbstractDataTable implements DataTableInterface
{
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

    public function getDataPath(): ?string
    {
        return null;
    }
}
