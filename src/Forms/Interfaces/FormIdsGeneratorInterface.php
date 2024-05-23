<?php

declare(strict_types=1);

namespace Laniakea\Forms\Interfaces;

interface FormIdsGeneratorInterface
{
    /**
     * Generate form ID.
     *
     * @return string
     */
    public function getFormId(): string;

    /**
     * Generate section ID.
     *
     * @param string|null $label
     *
     * @return string
     */
    public function getSectionId(?string $label): string;

    /**
     * Generate field ID.
     *
     * @param string      $name
     * @param string|null $label
     *
     * @return string
     */
    public function getFieldId(string $name, ?string $label): string;
}
