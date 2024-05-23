<?php

declare(strict_types=1);

namespace Laniakea\Settings;

use Illuminate\Support\Arr;
use Laniakea\Settings\Interfaces\HasSettingsInterface;
use Laniakea\Settings\Interfaces\SettingInterface;
use Laniakea\Settings\Interfaces\SettingsGeneratorInterface;
use Laniakea\Settings\Interfaces\SettingsValuesInterface;

readonly class SettingsValues implements SettingsValuesInterface
{
    public function __construct(private SettingsGeneratorInterface $generator)
    {
        //
    }

    public function getDefaults(string $enum): array
    {
        $values = [];
        $settings = $this->generator->getSettings($enum);

        foreach ($settings as $setting) {
            $values[$setting->getName()] = $setting->getSettingAttribute()->getDefaultValue();
        }

        return $values;
    }

    public function getSettingsForCreate(string $enum, ?array $settings): array
    {
        return $this->toPersisted($enum, $settings ?? []);
    }

    public function getSettingsForUpdate(HasSettingsInterface $model, ?array $settings): array
    {
        $persisted = $this->toPersisted(
            $model->getSettingsEnum(),
            $settings ?? [],
        );

        return array_merge($model->getCurrentSettings() ?? [], $persisted);
    }

    public function toPersisted(string $enum, array $payload, bool $ignoreRequestPaths = false): array
    {
        $settings = $this->generator->getSettings($enum);
        $values = [];

        foreach ($settings as $setting) {
            $key = $this->getPayloadKey($setting, $ignoreRequestPaths);

            if (!Arr::has($payload, $key)) {
                continue;
            }

            $value = Arr::get($payload, $key);

            if (!$setting->getSettingAttribute()->isValid($value)) {
                continue;
            }

            $values = array_merge(
                $values,
                $setting->getSettingAttribute()->toPersisted($setting->getName(), $value, $payload),
            );
        }

        return $values;
    }

    public function fromPersisted(string $enum, array $persisted): array
    {
        $settings = $this->generator->getSettings($enum);
        $values = $this->getDefaults($enum);

        foreach ($settings as $setting) {
            if (!array_key_exists($setting->getName(), $persisted)) {
                continue;
            }

            $values = array_merge(
                $values,
                $setting->getSettingAttribute()->fromPersisted($setting->getName(), $persisted[$setting->getName()], $persisted),
            );
        }

        return $values;
    }

    protected function getPayloadKey(SettingInterface $setting, bool $ignoreRequestPaths): string
    {
        if ($ignoreRequestPaths || is_null($setting->getRequestPath())) {
            return $setting->getName();
        }

        return $setting->getRequestPath();
    }
}
