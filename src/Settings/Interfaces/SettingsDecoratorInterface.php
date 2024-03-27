<?php

declare(strict_types=1);

namespace Laniakea\Settings\Interfaces;

interface SettingsDecoratorInterface
{
    /**
     * Get value for the given setting.
     *
     * @param \BackedEnum|string $name
     * @param mixed|null         $default
     *
     * @return mixed
     */
    public function getValue(\BackedEnum|string $name, mixed $default = null): mixed;

    /**
     * Get all settings values.
     *
     * @return array
     */
    public function getSettings(): array;

    /**
     * Update settings with given values.
     * Use $ignoreRequestPaths to skip request path checks (or use `fill` method).
     *
     * @param array $settings
     * @param bool  $ignoreRequestPaths
     *
     * @return $this
     */
    public function update(array $settings, bool $ignoreRequestPaths = false): static;

    /**
     * Update settings with given values and ignore request paths.
     *
     * @param array $settings
     *
     * @return $this
     */
    public function fill(array $settings): static;
}
