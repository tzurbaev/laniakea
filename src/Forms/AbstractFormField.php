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

    /**
     * Form field ID.
     *
     * @return string|null
     */
    public function getId(): ?string
    {
        return $this->id;
    }

    /**
     * Set form field ID.
     *
     * @param string $id
     *
     * @return $this
     */
    public function setId(string $id): static
    {
        $this->id = $id;

        return $this;
    }

    /**
     * Form field name.
     *
     * @return string|null
     */
    public function getLabel(): ?string
    {
        return $this->label;
    }

    /**
     * Set form field name.
     *
     * @param string $label
     *
     * @return $this
     */
    public function setLabel(string $label): static
    {
        $this->label = $label;

        return $this;
    }

    /**
     * Form field hint.
     *
     * @return string|null
     */
    public function getHint(): ?string
    {
        return $this->hint;
    }

    /**
     * Set form field hint.
     *
     * @param string $hint
     *
     * @return $this
     */
    public function setHint(string $hint): static
    {
        $this->hint = $hint;

        return $this;
    }

    /**
     * Set single setting value.
     *
     * @param string $key
     * @param mixed  $value
     *
     * @return $this
     */
    public function setSetting(string $key, mixed $value): static
    {
        Arr::set($this->settings, $key, $value);

        return $this;
    }

    /**
     * Set multiple setting values at once.
     *
     * @param array $settings
     *
     * @return $this
     */
    public function setSettings(array $settings): static
    {
        foreach ($settings as $key => $value) {
            $this->setSetting($key, $value);
        }

        return $this;
    }

    /**
     * Get form field settings.
     *
     * @return array
     */
    public function getSettings(): array
    {
        $attributes = $this->getAttributes();

        return [
            ...$this->getDefaultSettings(),
            ...$this->settings,
            ...(!empty($attributes) ? ['attributes' => $attributes] : []),
        ];
    }

    /**
     * Get form field attributes.
     *
     * @return array
     */
    public function getAttributes(): array
    {
        return [
            ...$this->getDefaultAttributes(),
            ...$this->attributes,
        ];
    }

    /**
     * Set single attribute value.
     *
     * @param string $key
     * @param mixed  $value
     *
     * @return $this
     */
    public function setAttribute(string $key, mixed $value): static
    {
        $this->attributes[$key] = $value;

        return $this;
    }

    /**
     * Merge attributes with existing ones.
     *
     * @param array $attributes
     *
     * @return $this
     */
    public function setAttributes(array $attributes): static
    {
        foreach ($attributes as $key => $value) {
            $this->setAttribute($key, $value);
        }

        return $this;
    }

    /**
     * Set readonly attribute to the field.
     *
     * @param bool $value
     *
     * @return $this
     */
    public function setReadOnly(bool $value = true): static
    {
        $this->setAttribute('readonly', $value);

        return $this;
    }

    /**
     * Set disabled attribute to the field.
     *
     * @param bool $value
     *
     * @return $this
     */
    public function setDisabled(bool $value = true): static
    {
        return $this->setAttribute('disabled', $value);
    }

    /**
     * Set required attribute to the field.
     *
     * @param bool $required
     *
     * @return $this
     */
    public function setRequired(bool $required = true): static
    {
        return $this->setAttribute('required', $required);
    }
}
