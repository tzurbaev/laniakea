<?php

declare(strict_types=1);

namespace Laniakea\Forms;

use Illuminate\Contracts\Support\MessageBag;
use Laniakea\Forms\Interfaces\FormInterface;

abstract class AbstractForm implements FormInterface
{
    protected array $settings = [];

    public function getId(): ?string
    {
        return null;
    }

    public function getLayout(): ?string
    {
        return null;
    }

    public function getHttpHeaders(): array
    {
        return [];
    }

    public function getErrors(): ?MessageBag
    {
        return null;
    }

    public function getRedirectUrl(): ?string
    {
        return null;
    }

    public function getSettings(): array
    {
        return [
            ...$this->getDefaultSettings(),
            ...$this->settings,
        ];
    }

    protected function getDefaultSettings(): array
    {
        return [];
    }
}
