<?php

declare(strict_types=1);

namespace Laniakea\Forms\Fields;

class RadioGroupField extends AbstractFormFieldWithOptions
{
    public function getType(): string
    {
        return 'RadioGroupField';
    }
}
