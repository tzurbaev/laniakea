<?php

declare(strict_types=1);

namespace Laniakea\Settings;

use Laniakea\Settings\Interfaces\HasSettingsInterface;
use Laniakea\Settings\Interfaces\SettingsUpdaterInterface;
use Laniakea\Settings\Interfaces\SettingsValuesInterface;

readonly class SettingsUpdater implements SettingsUpdaterInterface
{
    public function __construct(private SettingsValuesInterface $values)
    {
        //
    }

    public function update(HasSettingsInterface $model, array $settings, bool $ignoreRequestPaths = false): array
    {
        $persisted = $this->values->toPersisted(
            $model->getSettingsEnum(),
            $settings,
            $ignoreRequestPaths,
        );

        $values = array_merge($model->getCurrentSettings() ?? [], $persisted);

        $model->updateSettings($values);

        return $values;
    }
}
