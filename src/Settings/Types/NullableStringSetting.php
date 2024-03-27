<?php

declare(strict_types=1);

namespace Laniakea\Settings\Types;

use Laniakea\Settings\Interfaces\SettingAttributeInterface;

#[\Attribute]
readonly class NullableStringSetting implements SettingAttributeInterface
{
    public function __construct(private ?string $default, private bool $validateEmpty = false)
    {
        //
    }

    public function getDefaultValue(): ?string
    {
        return $this->default;
    }

    public function isValid(mixed $value): bool
    {
        if (is_null($value)) {
            return true;
        } elseif (!is_string($value)) {
            return false;
        } elseif (!$this->validateEmpty && !strlen($value)) {
            return false;
        }

        return true;
    }

    public function toPersisted(string $key, mixed $value, array $settings): array
    {
        return [$key => $value];
    }

    public function fromPersisted(string $key, mixed $value, array $settings): array
    {
        return [$key => $value];
    }
}
