<?php

declare(strict_types=1);

namespace Laniakea\Forms;

use Laniakea\Forms\Enums\FormButtonType;
use Laniakea\Forms\Interfaces\FormButtonInterface;

readonly class FormButton implements FormButtonInterface
{
    public function __construct(
        protected FormButtonType $type,
        protected ?string $label = null,
        protected ?string $url = null,
        protected array $settings = [],
    ) {
        //
    }

    public function getType(): FormButtonType
    {
        return $this->type;
    }

    public function getLabel(): ?string
    {
        return $this->label;
    }

    public function getUrl(): ?string
    {
        return $this->url;
    }

    public function getSettings(): array
    {
        return $this->settings;
    }
}
