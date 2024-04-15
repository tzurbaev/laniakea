<?php

declare(strict_types=1);

namespace Laniakea\DataTables\Interfaces;

interface DataTableViewInterface
{
    public function getId(): mixed;

    public function getName(): string;

    public function getSettings(): array;
}
