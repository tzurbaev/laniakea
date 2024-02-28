<?php

declare(strict_types=1);

namespace Laniakea\Forms\Fields;

use Laniakea\Forms\AbstractFormField;

class HiddenField extends AbstractFormField
{
    public function getType(): string
    {
        return 'HiddenField';
    }
}
