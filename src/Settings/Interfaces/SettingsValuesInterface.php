<?php

declare(strict_types=1);

namespace Laniakea\Settings\Interfaces;

interface SettingsValuesInterface
{
    /**
     * Get default values for given settings enum.
     *
     * @param string $enum
     *
     * @return array
     */
    public function getDefaults(string $enum): array;

    /**
     * Generate settings list for create.
     *
     * @param string     $enum
     * @param array|null $settings
     *
     * @return array
     */
    public function getSettingsForCreate(string $enum, ?array $settings): array;

    /**
     * Generate settings values for update.
     *
     * @param HasSettingsInterface $model
     * @param array|null           $settings
     *
     * @return array
     */
    public function getSettingsForUpdate(HasSettingsInterface $model, ?array $settings): array;

    /**
     * Prepare given payload for persisted storage.
     *
     * @param string $enum
     * @param array  $payload
     * @param bool   $ignoreRequestPaths
     *
     * @return array
     */
    public function toPersisted(string $enum, array $payload, bool $ignoreRequestPaths = false): array;

    /**
     * Generate values from persisted value.
     *
     * @param string $enum
     * @param array  $persisted
     *
     * @return array
     */
    public function fromPersisted(string $enum, array $persisted): array;
}
