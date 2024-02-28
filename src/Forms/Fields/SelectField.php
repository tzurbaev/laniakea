<?php

declare(strict_types=1);

namespace Laniakea\Forms\Fields;

class SelectField extends AbstractFormFieldWithOptions
{
    public function getType(): string
    {
        return 'SelectField';
    }
}
