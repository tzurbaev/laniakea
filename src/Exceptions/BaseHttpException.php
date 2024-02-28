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

    public function setMeta(array $meta): static
    {
        $this->meta = $meta;

        return $this;
    }

    public function addMeta(array $meta): static
    {
        $this->meta = array_merge($this->meta, $meta);

        return $this;
    }

    public function setHeaders(array $headers): static
    {
        $this->headers = $headers;

        return $this;
    }

    public function addHeaders(array $headers): static
    {
        $this->headers = array_merge($this->headers, $headers);

        return $this;
    }

    public function getErrorCode(): string
    {
        return static::ERROR_CODE;
    }

    public function getHttpCode(): int
    {
        return static::HTTP_CODE;
    }

    public function getErrorMessage(): string
    {
        return $this->getTranslatedErrorMessage() ?? $this->getOriginalMessage();
    }

    public function getTranslatedErrorMessage(): ?string
    {
        return trans()->has($this->getTranslationPath()) ? trans($this->getTranslationPath(), $this->getMeta()) : null;
    }

    public function getOriginalMessage(): string
    {
        return $this->getMessage() ?: static::MESSAGE;
    }

    public function getHeaders(): array
    {
        return $this->headers;
    }

    public function getMeta(): array
    {
        return $this->meta;
    }

    protected function getTranslationNamespace(): string
    {
        return 'exceptions.';
    }

    protected function getTranslationPath(): string
    {
        return $this->getTranslationNamespace().$this->getErrorCode().'.message';
    }
}
