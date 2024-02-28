<?php

declare(strict_types=1);

namespace Laniakea\Forms\Interfaces;

use Laniakea\Forms\Enums\FormButtonType;

interface FormButtonInterface
{
    public function getType(): FormButtonType;

    public function getLabel(): ?string;

    public function getUrl(): ?string;

    public function getSettings(): array;
}
