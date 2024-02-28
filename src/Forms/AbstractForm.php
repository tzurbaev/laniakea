<?php

declare(strict_types=1);

namespace Laniakea\Forms;

use Laniakea\Forms\Interfaces\FormInterface;

abstract class AbstractForm implements FormInterface
{
    protected array $settings = [];

    protected function getDefaultSettings(): array
    {
        return [];
    }

    public function getId(): ?string
    {
        return null;
    }

    public function getHttpHeaders(): array
    {
        return [];
    }

    public function getSettings(): array
    {
        return [
            ...$this->getDefaultSettings(),
            ...$this->settings,
        ];
    }
}
