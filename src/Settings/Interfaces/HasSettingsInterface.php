<?php

declare(strict_types=1);

namespace Laniakea\Settings\Interfaces;

interface HasSettingsInterface
{
    /**
     * Get settings enum class name.
     *
     * @return string
     */
    public function getSettingsEnum(): string;

    /**
     * Get current model settings.
     *
     * @return array|null
     */
    public function getCurrentSettings(): ?array;

    /**
     * Write settings to persisted storage.
     *
     * @param array $settings
     */
    public function updateSettings(array $settings): void;
}
