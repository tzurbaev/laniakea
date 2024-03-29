<?php

declare(strict_types=1);

namespace Laniakea\Forms\Interfaces;

use Illuminate\Contracts\Support\MessageBag;

interface FormInterface
{
    public function getId(): ?string;

    public function getLayout(): ?string;

    public function getMethod(): string;

    public function getUrl(): string;

    public function getRedirectUrl(): ?string;

    public function getHttpHeaders(): array;

    public function getFields(): array;

    public function getValues(): array;

    public function getErrors(): ?MessageBag;

    public function getSections(): array;

    public function getButtons(): array;

    public function getSettings(): array;
}
