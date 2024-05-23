<?php

declare(strict_types=1);

namespace Laniakea\Exceptions;

class BaseHttpException extends \RuntimeException
{
    public const MESSAGE = 'Something went wrong.';
    public const ERROR_CODE = 'internal';
    public const HTTP_CODE = 500;

    protected array $headers = [];
    protected array $meta = [];

    /**
     * Replace meta data that should be included in the response.
     *
     * @param array $meta
     *
     * @return $this
     */
    public function setMeta(array $meta): static
    {
        $this->meta = $meta;

        return $this;
    }

    /**
     * Add meta data that should be included in the response.
     *
     * @param array $meta
     *
     * @return $this
     */
    public function addMeta(array $meta): static
    {
        $this->meta = array_merge($this->meta, $meta);

        return $this;
    }

    /**
     * Replace HTTP headers that should be sent with the response.
     *
     * @param array $headers
     *
     * @return $this
     */
    public function setHeaders(array $headers): static
    {
        $this->headers = $headers;

        return $this;
    }

    /**
     * Add HTTP headers that should be sent with the response.
     *
     * @param array $headers
     *
     * @return $this
     */
    public function addHeaders(array $headers): static
    {
        $this->headers = array_merge($this->headers, $headers);

        return $this;
    }

    /**
     * Get the error code.
     *
     * @return string
     */
    public function getErrorCode(): string
    {
        return static::ERROR_CODE;
    }

    /**
     * Get the HTTP status code.
     *
     * @return int
     */
    public function getHttpCode(): int
    {
        return static::HTTP_CODE;
    }

    /**
     * Get translated or original error message.
     *
     * @return string
     */
    public function getErrorMessage(): string
    {
        return $this->getTranslatedErrorMessage() ?? $this->getOriginalMessage();
    }

    /**
     * Get translated error message.
     *
     * @return string|null
     */
    public function getTranslatedErrorMessage(): ?string
    {
        return trans()->has($this->getTranslationPath()) ? trans($this->getTranslationPath(), $this->getMeta()) : null;
    }

    /**
     * Get original error message.
     *
     * @return string
     */
    public function getOriginalMessage(): string
    {
        return $this->getMessage() ?: static::MESSAGE;
    }

    /**
     * Get HTTP headers.
     *
     * @return array
     */
    public function getHeaders(): array
    {
        return $this->headers;
    }

    /**
     * Get meta data.
     *
     * @return array
     */
    public function getMeta(): array
    {
        return $this->meta;
    }

    /**
     * Get the translation namespace that will be used in error message translation.
     *
     * @return string
     */
    protected function getTranslationNamespace(): string
    {
        return 'exceptions.';
    }

    /**
     * Get the translation path for the current error message.
     *
     * @return string
     */
    protected function getTranslationPath(): string
    {
        return $this->getTranslationNamespace().$this->getErrorCode().'.message';
    }
}
