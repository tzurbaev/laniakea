<?php

declare(strict_types=1);

namespace Laniakea\Settings\Types;

use Laniakea\Settings\Interfaces\SettingAttributeInterface;

#[\Attribute]
readonly class NullableEnumSetting implements SettingAttributeInterface
{
    /** @param \BackedEnum|string|null $enum */
    public function __construct(private ?\BackedEnum $default, private ?string $enum = null)
    {
        if (is_null($this->default) && is_null($this->enum)) {
            throw new \InvalidArgumentException('Default value or enum class must be provided.');
        }
    }

    public function getDefaultValue(): ?\BackedEnum
    {
        return $this->default;
    }

    public function isValid(mixed $value): bool
    {
        if (is_null($value)) {
            return true;
        }

        $enum = !is_null($this->default) ? get_class($this->default) : $this->enum;

        if ($value instanceof \BackedEnum) {
            return $value instanceof $enum;
        }

        // Check types of default value (or first case of enum) & given value to avoid `tryFrom()` type errors.

        $possibleValue = !is_null($this->default) ? $this->default : $this->enum::cases()[0];

        if (is_string($possibleValue->value) && is_string($value)) {
            return !is_null($enum::tryFrom($value));
        } elseif (is_int($possibleValue->value) && is_int($value)) {
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
        $enum = !is_null($this->default) ? get_class($this->default) : $this->enum;

        return [
            $key => is_null($value) ? null : $enum::from($value),
        ];
    }
}
