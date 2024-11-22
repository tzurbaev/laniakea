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

    /**
     * Section ID.
     *
     * @return string|null
     */
    public function getId(): ?string
    {
        return $this->id;
    }

    /**
     * Section label.
     *
     * @return string|null
     */
    public function getLabel(): ?string
    {
        return $this->label;
    }

    /**
     * Section description.
     *
     * @return string|null
     */
    public function getDescription(): ?string
    {
        return $this->description;
    }

    /**
     * Section fields.
     *
     * @return array
     */
    public function getFieldNames(): array
    {
        return $this->fieldNames;
    }
}
