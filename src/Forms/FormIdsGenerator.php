<?php

declare(strict_types=1);

namespace Laniakea\Forms;

use Illuminate\Support\Str;
use Laniakea\Forms\Interfaces\FormIdsGeneratorInterface;

class FormIdsGenerator implements FormIdsGeneratorInterface
{
    /**
     * Generate form ID.
     *
     * @return string
     */
    public function getFormId(): string
    {
        return 'Form-'.Str::random();
    }

    /**
     * Generate section ID.
     *
     * @param string|null $label
     *
     * @return string
     */
    public function getSectionId(?string $label): string
    {
        return is_null($label) ? 'Section-'.Str::random() : 'Section-'.Str::slug($label);
    }

    /**
     * Generate field ID.
     *
     * @param string      $name
     * @param string|null $label
     *
     * @return string
     */
    public function getFieldId(string $name, ?string $label): string
    {
        return is_null($label) ? 'Field-'.Str::slug($name) : 'Field-'.Str::slug($name).'-'.Str::slug($label);
    }
}
