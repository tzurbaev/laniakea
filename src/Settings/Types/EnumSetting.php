<?php

declare(strict_types=1);

namespace Laniakea\Settings\Types;

use Laniakea\Settings\Interfaces\SettingAttributeInterface;

#[\Attribute]
readonly class EnumSetting implements SettingAttributeInterface
{
    public function __construct(private \BackedEnum $default)
    {
        //
    }

    public function getDefaultValue(): \BackedEnum
    {
        return $this->default;
    }

    public function isValid(mixed $value): bool
    {
        $enum = get_class($this->default);

        if ($value instanceof \BackedEnum) {
            return $value instanceof $enum;
        }

        // Check types of default value & given value to avoid `tryFrom()` type errors.

        if (is_string($this->default->value) && is_string($value)) {
            return !is_null($enum::tryFrom($value));
        } elseif (is_int($this->default->value) && is_int($value)) {
            return !is_null($enum::tryFrom($value));
        }

        return false;
    }

    public function toPersisted(string $key, mixed $value, array $settings): array
    {
        return [
            $key => $value instanceof \BackedEnum ? $value->value : $value,
        ];
    }

    public function fromPersisted(string $key, mixed $value, array $settings): array
    {
        $enum = get_class($this->default);

        return [$key => $enum::from($value)];
    }
}
