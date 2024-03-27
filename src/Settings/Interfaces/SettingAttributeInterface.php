<?php

declare(strict_types=1);

namespace Laniakea\Settings\Interfaces;

interface SettingAttributeInterface
{
    /**
     * Get default value for current setting.
     *
     * @return mixed
     */
    public function getDefaultValue(): mixed;

    /**
     * Check if provided value is valid for current setting.
     *
     * @param mixed $value
     *
     * @return bool
     */
    public function isValid(mixed $value): bool;

    /**
     * Get payload for persisted storage.
     *
     * @param string $key
     * @param mixed  $value
     * @param array  $settings
     *
     * @return array
     */
    public function toPersisted(string $key, mixed $value, array $settings): array;

    /**
     * Get payload from persisted value.
     *
     * @param string $key
     * @param mixed  $value
     * @param array  $settings
     *
     * @return array
     */
    public function fromPersisted(string $key, mixed $value, array $settings): array;
}
