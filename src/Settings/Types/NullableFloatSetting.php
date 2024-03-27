<?php

declare(strict_types=1);

namespace Laniakea\Settings\Types;

use Laniakea\Settings\Interfaces\SettingAttributeInterface;

#[\Attribute]
readonly class NullableFloatSetting implements SettingAttributeInterface
{
    public function __construct(private ?float $default, private bool $validateString = false)
    {
        //
    }

    public function getDefaultValue(): ?float
    {
        return $this->default;
    }

    public function isValid(mixed $value): bool
    {
        if (is_null($value)) {
            return true;
        } elseif (is_float($value)) {
            return true;
        } elseif (!$this->validateString || !is_string($value) || !is_numeric($value)) {
            return false;
        }

        return str_contains($value, '.');
    }

    public function toPersisted(string $key, mixed $value, array $settings): array
    {
        return [$key => is_null($value) ? null : floatval($value)];
    }

    public function fromPersisted(string $key, mixed $value, array $settings): array
    {
        return [$key => is_null($value) ? null : floatval($value)];
    }
}
