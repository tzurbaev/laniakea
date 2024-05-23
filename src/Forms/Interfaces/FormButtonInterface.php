<?php

declare(strict_types=1);

namespace Laniakea\Forms\Interfaces;

use Laniakea\Forms\Enums\FormButtonType;

interface FormButtonInterface
{
    /**
     * Form button type,
     *
     * @return FormButtonType
     */
    public function getType(): FormButtonType;

    /**
     * Form button label.
     *
     * @return string|null
     */
    public function getLabel(): ?string;

    /**
     * Form button URL (if it's a link).
     *
     * @return string|null
     */
    public function getUrl(): ?string;

    /**
     * Additional settings for the button.
     *
     * @return array
     */
    public function getSettings(): array;
}
