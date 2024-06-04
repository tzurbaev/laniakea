<?php

declare(strict_types=1);

namespace Laniakea\DataTables;

use Laniakea\DataTables\Interfaces\DataTableColumnInterface;
use Laniakea\DataTables\Interfaces\DataTableColumnSortingInterface;

class DataTableColumn implements DataTableColumnInterface
{
    public function __construct(
        protected string $type,
        protected ?string $label = null,
        protected ?DataTableColumnSortingInterface $sorting = null,
        protected array $settings = []
    ) {
        //
    }

    public function getType(): string
    {
        return $this->type;
    }

    public function getLabel(): ?string
    {
        return $this->label;
    }

    public function getSorting(): ?DataTableColumnSortingInterface
    {
        return $this->sorting;
    }

    public function setSorting(?DataTableColumnSortingInterface $sorting): static
    {
        $this->sorting = $sorting;

        return $this;
    }

    public function getSettings(): array
    {
        return $this->settings;
    }

    public function setSettings(array $settings): static
    {
        $this->settings = $settings;

        return $this;
    }
}
