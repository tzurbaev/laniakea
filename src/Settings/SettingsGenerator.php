<?php

declare(strict_types=1);

namespace Laniakea\Settings;

use Laniakea\Settings\Interfaces\RequestPathAttributeInterface;
use Laniakea\Settings\Interfaces\SettingAttributeInterface;
use Laniakea\Settings\Interfaces\SettingInterface;
use Laniakea\Settings\Interfaces\SettingsGeneratorInterface;

readonly class SettingsGenerator implements SettingsGeneratorInterface
{
    /**
     * Generates settings list for a given enum class.
     *
     * @param string $enum
     *
     * @throws \ReflectionException
     *
     * @return array<int, SettingInterface>
     */
    public function getSettings(string $enum): array
    {
        $settings = [];
        $constants = (new \ReflectionClass($enum))->getReflectionConstants();

        foreach ($constants as $constant) {
            $settings[] = $this->getSetting($constant);
        }

        return array_values(
            array_filter($settings)
        );
    }

    protected function getSetting(\ReflectionClassConstant $constant): ?SettingInterface
    {
        $value = $constant->getValue();

        if (!($value instanceof \BackedEnum)) {
            return null;
        }

        $settingAttribute = $this->getAttributeInstance($constant, SettingAttributeInterface::class);

        if (is_null($settingAttribute)) {
            return null;
        }

        $requestPathAttribute = $this->getAttributeInstance($constant, RequestPathAttributeInterface::class);

        return new Setting($value, $settingAttribute, $requestPathAttribute?->getPath());
    }

    protected function getAttributeInstance(\ReflectionClassConstant $constant, string $class): SettingAttributeInterface|RequestPathAttributeInterface|null
    {
        $attributes = $constant->getAttributes($class, \ReflectionAttribute::IS_INSTANCEOF);

        if (!count($attributes)) {
            return null;
        }

        return $attributes[0]->newInstance();
    }
}
