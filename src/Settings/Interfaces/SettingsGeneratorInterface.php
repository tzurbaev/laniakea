<?php

declare(strict_types=1);

namespace Laniakea\Settings\Interfaces;

interface SettingsGeneratorInterface
{
    /**
     * Generate settings list for given enum.
     *
     * @param string $enum
     *
     * @return array|SettingInterface[]
     */
    public function getSettings(string $enum): array;
}
