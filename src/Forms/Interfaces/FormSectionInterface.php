<?php

declare(strict_types=1);

namespace Laniakea\Forms\Interfaces;

interface FormSectionInterface
{
    /**
     * Section ID.
     *
     * @return string|null
     */
    public function getId(): ?string;

    /**
     * Section label.
     *
     * @return string|null
     */
    public function getLabel(): ?string;

    /**
     * Section description.
     *
     * @return string|null
     */
    public function getDescription(): ?string;

    /**
     * Section fields.
     *
     * @return array
     */
    public function getFieldNames(): array;
}
