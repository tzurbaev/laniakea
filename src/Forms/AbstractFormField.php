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
    protected array $attributes = [];

    public function __construct(protected ?string $label = null)
    {
        //
    }

    /**
     * Default form field settings that can be overridden by setSetting/setSettings methods.
     *
     * @return array
     */
    protected function getDefaultSettings(): array
    {
        return [];
    }

    /**
     * Default form field attributes that can be overridden by setAttribute/setAttributes methods.
     *
     * @return array
     */
    protected function getDefaultAttributes(): array
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
        foreach ($settings as $key => $value) {
            $this->setSetting($key, $value);
        }

        return $this;
    }

    public function getSettings(): array
    {
        $attributes = $this->getAttributes();

        return [
            ...$this->getDefaultSettings(),
            ...$this->settings,
            ...(!empty($attributes) ? ['attributes' => $attributes] : []),
        ];
    }

    public function getAttributes(): array
    {
        return [
            ...$this->getDefaultAttributes(),
            ...$this->attributes,
        ];
    }

    public function setAttribute(string $key, mixed $value): static
    {
        $this->attributes[$key] = $value;

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
        return $this->setAttribute('disabled', $value);
    }

    public function setRequired(bool $required = true): static
    {
        return $this->setAttribute('required', $required);
    }
}
