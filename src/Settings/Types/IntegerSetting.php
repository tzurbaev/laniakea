<?php

declare(strict_types=1);

namespace Laniakea\Settings\Types;

use Laniakea\Settings\Interfaces\SettingAttributeInterface;

#[\Attribute]
readonly class IntegerSetting implements SettingAttributeInterface
{
    public function __construct(private int $default, private bool $validateString = false)
    {
        //
    }

    public function getDefaultValue(): int
    {
        return $this->default;
    }

    public function isValid(mixed $value): bool
    {
        if (is_int($value)) {
            return true;
        } elseif (!$this->validateString || !is_string($value) || !is_numeric($value)) {
            return false;
        }

        return !str_contains($value, '.');
    }

    public function toPersisted(string $key, mixed $value, array $settings): array
    {
        return [$key => intval($value)];
    }

    public function fromPersisted(string $key, mixed $value, array $settings): array
    {
        return [$key => intval($value)];
    }
}
