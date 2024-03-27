<?php

declare(strict_types=1);

namespace Laniakea\Settings\Types;

use Laniakea\Settings\Interfaces\SettingAttributeInterface;

#[\Attribute]
readonly class EnumArraySetting extends AbstractArraySetting implements SettingAttributeInterface
{
    /** @param \BackedEnum|string $enum */
    public function __construct(
        private array $default,
        private string $enum,
        private bool $validateEmpty = false,
    ) {
        //
    }

    protected function getCases(): array
    {
        return array_map(fn (\BackedEnum $item) => $item->value, $this->enum::cases());
    }

    protected function validateEmpty(): bool
    {
        return $this->validateEmpty;
    }

    public function getDefaultValue(): array
    {
        return $this->default;
    }

    public function isValid(mixed $value): bool
    {
        return $this->isValidArray(
            $value,
            fn (mixed $item) => $item instanceof \BackedEnum ? $item->value : $item,
        );
    }

    public function toPersisted(string $key, mixed $value, array $settings): array
    {
        return [
            $key => array_map(fn (mixed $item) => $item instanceof \BackedEnum ? $item->value : $item, $value),
        ];
    }

    public function fromPersisted(string $key, mixed $value, array $settings): array
    {
        return [
            $key => array_map(fn (mixed $item) => $this->enum::from($item), $value),
        ];
    }
}
