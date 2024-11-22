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

    /**
     * Form button type,
     *
     * @return FormButtonType
     */
    public function getType(): FormButtonType
    {
        return $this->type;
    }

    /**
     * Form button label.
     *
     * @return string|null
     */
    public function getLabel(): ?string
    {
        return $this->label;
    }

    /**
     * Form button URL (if it's a link).
     *
     * @return string|null
     */
    public function getUrl(): ?string
    {
        return $this->url;
    }

    /**
     * Additional settings for the button.
     *
     * @return array
     */
    public function getSettings(): array
    {
        return $this->settings;
    }
}
