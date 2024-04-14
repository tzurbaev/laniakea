<?php

declare(strict_types=1);

namespace Laniakea\DataTables;

use Illuminate\Http\Request;
use Laniakea\DataTables\Interfaces\DataTableFilterInterface;

class DataTableFilter implements DataTableFilterInterface
{
    public function __construct(
        protected readonly string $type,
        protected readonly string $fieldName,
        protected readonly ?string $label = null,
        protected array $settings = [],
    ) {
        //
    }

    public function getType(): string
    {
        return $this->type;
    }

    public function getFieldName(): string
    {
        return $this->fieldName;
    }

    public function getLabel(): ?string
    {
        return $this->label;
    }

    public function getCurrentValue(Request $request): mixed
    {
        return $request->input($this->getFieldName());
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
