<?php

declare(strict_types=1);

namespace Laniakea\Forms;

use Illuminate\Support\Arr;
use Laniakea\Forms\Interfaces\FormFieldInterface;

abstract class AbstractFormField implements FormFieldInterface
{
    protected ?string $id = null;
    protected ?string $hint = null;
    protected array $settings = [];

    public function __construct(protected ?string $label = null)
    {
        //
    }

    protected function getDefaultSettings(): array
    {
        return [];
    }

    public function getId(): ?string
    {
        return $this->id;
    }

    public function setId(string $id): static
    {
        $this->id = $id;

        return $this;
    }

    public function getLabel(): ?string
    {
        return $this->label;
    }

    public function setLabel(string $label): static
    {
        $this->label = $label;

        return $this;
    }

    public function getHint(): ?string
    {
        return $this->hint;
    }

    public function setHint(string $hint): static
    {
        $this->hint = $hint;

        return $this;
    }

    public function setSetting(string $key, mixed $value): static
    {
        Arr::set($this->settings, $key, $value);

        return $this;
    }

    public function setSettings(array $settings): static
    {
        $this->settings = $settings;

        return $this;
    }

    public function getSettings(): array
    {
        return [
            ...$this->getDefaultSettings(),
            ...$this->settings,
        ];
    }

    public function setAttribute(string $key, mixed $value): static
    {
        $this->setSetting('attributes.'.$key, $value);

        return $this;
    }

    public function setAttributes(array $attributes): static
    {
        foreach ($attributes as $key => $value) {
            $this->setAttribute($key, $value);
        }

        return $this;
    }

    public function setReadOnly(bool $value = true): static
    {
        $this->setAttribute('readonly', $value);

        return $this;
    }

    public function setDisabled(bool $value = true): static
    {
        $this->setAttribute('disabled', $value);

        return $this;
    }
}
