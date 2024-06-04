<?php

declare(strict_types=1);

namespace Laniakea\Forms\Fields;

use Laniakea\Forms\AbstractFormField;

class TextField extends AbstractFormField
{
    public function getType(): string
    {
        return 'TextField';
    }

    protected function getDefaultAttributes(): array
    {
        return [
            'type' => 'text',
        ];
    }

    public function setInputType(string $type): static
    {
        $this->setAttribute('type', $type);

        return $this;
    }

    public function setMinValue(int $length): static
    {
        $this->setAttribute('min', $length);

        return $this;
    }

    public function setMaxValue(int $length): static
    {
        $this->setAttribute('max', $length);

        return $this;
    }

    public function setStep(int $step): static
    {
        $this->setAttribute('step', $step);

        return $this;
    }
}
