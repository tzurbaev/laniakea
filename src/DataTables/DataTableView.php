<?php

declare(strict_types=1);

namespace Laniakea\DataTables;

use Laniakea\DataTables\Interfaces\DataTableViewInterface;

readonly class DataTableView implements DataTableViewInterface
{
    public function __construct(protected mixed $id, protected string $name, protected array $settings = [])
    {
        //
    }

    public function getId(): mixed
    {
        return $this->id;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getSettings(): array
    {
        return $this->settings;
    }
}
