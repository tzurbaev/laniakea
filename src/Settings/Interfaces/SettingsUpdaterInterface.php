<?php

declare(strict_types=1);

namespace Laniakea\Settings\Interfaces;

interface SettingsUpdaterInterface
{
    /**
     * Update settings for given model. This operation may write to persisted storage.
     *
     * @param HasSettingsInterface $model
     * @param array                $settings
     * @param bool                 $ignoreRequestPaths
     *
     * @return array
     */
    public function update(
        HasSettingsInterface $model,
        array $settings,
        bool $ignoreRequestPaths = false,
    ): array;
}
