<?php

declare(strict_types=1);

namespace Laniakea\Forms\Fields;

use Laniakea\Forms\AbstractFormField;

abstract class AbstractFormFieldWithOptions extends AbstractFormField
{
    public function __construct(?string $label = null, array $options = [])
    {
        parent::__construct($label);

        $this->setOptions($options);
    }

    protected function getDefaultSettings(): array
    {
        return [
            'options' => [],
        ];
    }

    public function setOptions(array $options): static
    {
        $this->setSetting('options', $options);

        return $this;
    }
}
