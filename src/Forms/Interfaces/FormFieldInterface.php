<?php

declare(strict_types=1);

namespace Laniakea\Forms\Interfaces;

interface FormFieldInterface
{
    public function getType(): string;

    public function getId(): ?string;

    public function setId(string $id): static;

    public function getLabel(): ?string;

    public function setLabel(string $label): static;

    public function getHint(): ?string;

    public function setHint(string $hint): static;

    public function setSetting(string $key, mixed $value): static;

    public function setSettings(array $settings): static;

    public function getSettings(): array;

    public function setAttribute(string $key, mixed $value): static;

    public function setAttributes(array $attributes): static;
}
