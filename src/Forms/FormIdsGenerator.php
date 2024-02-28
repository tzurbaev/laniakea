<?php

declare(strict_types=1);

namespace Laniakea\Forms;

use Illuminate\Support\Str;
use Laniakea\Forms\Interfaces\FormIdsGeneratorInterface;

class FormIdsGenerator implements FormIdsGeneratorInterface
{
    public function getFormId(): string
    {
        return 'Form-'.Str::random();
    }

    public function getSectionId(?string $label): string
    {
        return is_null($label) ? 'Section-'.Str::random() : 'Section-'.Str::slug($label);
    }

    public function getFieldId(string $name, ?string $label): string
    {
        return is_null($label) ? 'Field-'.Str::slug($name) : 'Field-'.Str::slug($name).'-'.Str::slug($label);
    }
}
