<?php

declare(strict_types=1);

namespace Laniakea\Settings\Types;

use Laniakea\Settings\Interfaces\SettingAttributeInterface;

#[\Attribute]
readonly class NullablePossibleValueSetting implements SettingAttributeInterface
{
    public function __construct(private mixed $default, private array $cases)
    {
        //
    }

    public function getDefaultValue(): mixed
    {
        return $this->default;
    }

    public function isValid(mixed $value): bool
    {
        return is_null($value) || in_array($value, $this->cases);
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
