<?php

declare(strict_types=1);

namespace Laniakea\Forms;

use Laniakea\Forms\Interfaces\FormSectionInterface;

readonly class FormSection implements FormSectionInterface
{
    public function __construct(
        private array $fieldNames,
        private ?string $label = null,
        private ?string $description = null,
        private ?string $id = null,
    ) {
        //
    }

    public function getId(): ?string
    {
        return $this->id;
    }

    public function getLabel(): ?string
    {
        return $this->label;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function getFieldNames(): array
    {
        return $this->fieldNames;
    }
}
