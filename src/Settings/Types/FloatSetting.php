<?php

declare(strict_types=1);

namespace Laniakea\Settings\Types;

use Laniakea\Settings\Interfaces\SettingAttributeInterface;

#[\Attribute]
readonly class FloatSetting implements SettingAttributeInterface
{
    public function __construct(private float $default, private bool $validateString = false)
    {
        //
    }

    public function getDefaultValue(): mixed
    {
        return $this->default;
    }

    public function isValid(mixed $value): bool
    {
        if (is_float($value)) {
            return true;
        } elseif (!$this->validateString || !is_string($value) || !is_numeric($value)) {
            return false;
        }

        return str_contains($value, '.');
    }

    public function toPersisted(string $key, mixed $value, array $settings): array
    {
        return [$key => floatval($value)];
    }

    public function fromPersisted(string $key, mixed $value, array $settings): array
    {
        return [$key => floatval($value)];
    }
}
