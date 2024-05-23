<?php

declare(strict_types=1);

namespace Laniakea\Forms\Interfaces;

use Illuminate\Contracts\Support\MessageBag;

interface FormInterface
{
    /**
     * Form ID.
     *
     * @return string|null
     */
    public function getId(): ?string;

    /**
     * Form layout type.
     *
     * @return string|null
     */
    public function getLayout(): ?string;

    /**
     * HTTP method that will be used to submit the form.
     *
     * @return string
     */
    public function getMethod(): string;

    /**
     * Form submission URL.
     *
     * @return string
     */
    public function getUrl(): string;

    /**
     * Form redirect URL.
     *
     * @return string|null
     */
    public function getRedirectUrl(): ?string;

    /**
     * HTTP headers that should be sent with the form submission.
     *
     * @return array
     */
    public function getHttpHeaders(): array;

    /**
     * Form fields.
     *
     * @return array
     */
    public function getFields(): array;

    /**
     * Form values.
     *
     * @return array
     */
    public function getValues(): array;

    /**
     * Validation errors.
     *
     * @return MessageBag|null
     */
    public function getErrors(): ?MessageBag;

    /**
     * Form sections.
     *
     * @return array
     */
    public function getSections(): array;

    /**
     * Form buttons.
     *
     * @return array
     */
    public function getButtons(): array;

    /**
     * Additional settings for the form.
     *
     * @return array
     */
    public function getSettings(): array;
}
