<?php

declare(strict_types=1);

namespace Laniakea\Forms\Fields;

use Laniakea\Forms\AbstractFormField;

class CheckboxField extends AbstractFormField
{
    public function getType(): string
    {
        return 'CheckboxField';
    }
}
