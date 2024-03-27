<?php

declare(strict_types=1);

namespace Laniakea\Settings\Types;

use Laniakea\Settings\Interfaces\SettingAttributeInterface;

#[\Attribute]
readonly class ArraySetting extends AbstractArraySetting implements SettingAttributeInterface
{
    public function __construct(
        private array $default,
        private array $cases = [],
        private bool $validateEmpty = false,
    ) {
        //
    }

    protected function getCases(): array
    {
        return $this->cases;
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
        return $this->isValidArray($value, fn ($item) => $item);
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
