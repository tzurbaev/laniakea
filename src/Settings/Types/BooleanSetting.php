<?php

declare(strict_types=1);

namespace Laniakea\Settings\Types;

use Laniakea\Settings\Interfaces\SettingAttributeInterface;

#[\Attribute]
readonly class BooleanSetting implements SettingAttributeInterface
{
    public function __construct(private bool $default)
    {
        //
    }

    public function getDefaultValue(): bool
    {
        return $this->default;
    }

    public function isValid(mixed $value): bool
    {
        return is_bool($value);
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
