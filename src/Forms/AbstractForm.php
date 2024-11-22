<?php

declare(strict_types=1);

namespace Laniakea\Forms;

use Illuminate\Contracts\Support\MessageBag;
use Laniakea\Forms\Interfaces\FormInterface;

abstract class AbstractForm implements FormInterface
{
    protected array $settings = [];

    /**
     * Form ID.
     *
     * @return string|null
     */
    public function getId(): ?string
    {
        return null;
    }

    /**
     * Form layout type.
     *
     * @return string|null
     */
    public function getLayout(): ?string
    {
        return null;
    }

    /**
     * Form redirect URL.
     *
     * @return string|null
     */
    public function getRedirectUrl(): ?string
    {
        return null;
    }

    /**
     * HTTP headers that should be sent with the form submission.
     *
     * @return array
     */
    public function getHttpHeaders(): array
    {
        return [];
    }

    /**
     * Validation errors for the current request.
     *
     * @return MessageBag|null
     */
    public function getErrors(): ?MessageBag
    {
        return null;
    }

    /**
     * Additional settings for the form.
     *
     * @return array
     */
    public function getSettings(): array
    {
        return [
            ...$this->getDefaultSettings(),
            ...$this->settings,
        ];
    }

    /**
     * Form's default settings.
     *
     * @return array
     */
    protected function getDefaultSettings(): array
    {
        return [];
    }
}
