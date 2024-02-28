<?php

declare(strict_types=1);

namespace Laniakea\Forms\Fields;

use Laniakea\Forms\AbstractFormField;

class TextareaField extends AbstractFormField
{
    public function getType(): string
    {
        return 'TextareaField';
    }

    public function setRows(int $rows): static
    {
        $this->setAttribute('rows', $rows);

        return $this;
    }

    public function setCols(int $cols): static
    {
        $this->setAttribute('cols', $cols);

        return $this;
    }
}
