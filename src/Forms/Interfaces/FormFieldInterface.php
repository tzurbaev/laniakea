<?php

declare(strict_types=1);

namespace Laniakea\Forms\Interfaces;

interface FormFieldInterface
{
    /**
     * Form field type.
     *
     * @return string
     */
    public function getType(): string;

    /**
     * Form field ID.
     *
     * @return string|null
     */
    public function getId(): ?string;

    /**
     * Set form field ID.
     *
     * @param string $id
     *
     * @return $this
     */
    public function setId(string $id): static;

    /**
     * Form field name.
     *
     * @return string|null
     */
    public function getLabel(): ?string;

    /**
     * Set form field name.
     *
     * @param string $label
     *
     * @return $this
     */
    public function setLabel(string $label): static;

    /**
     * Form field hint.
     *
     * @return string|null
     */
    public function getHint(): ?string;

    /**
     * Set form field hint.
     *
     * @param string $hint
     *
     * @return $this
     */
    public function setHint(string $hint): static;

    /**
     * Set single setting value.
     *
     * @param string $key
     * @param mixed  $value
     *
     * @return $this
     */
    public function setSetting(string $key, mixed $value): static;

    /**
     * Set multiple setting values at once.
     *
     * @param array $settings
     *
     * @return $this
     */
    public function setSettings(array $settings): static;

    /**
     * Get form field settings.
     *
     * @return array
     */
    public function getSettings(): array;

    /**
     * Set single attribute value.
     *
     * @param string $key
     * @param mixed  $value
     *
     * @return $this
     */
    public function setAttribute(string $key, mixed $value): static;

    /**
     * Merge attributes with existing ones.
     *
     * @param array $attributes
     *
     * @return $this
     */
    public function setAttributes(array $attributes): static;
}
