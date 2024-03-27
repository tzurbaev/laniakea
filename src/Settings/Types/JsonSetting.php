<?php

declare(strict_types=1);

namespace Laniakea\Settings\Types;

use Laniakea\Settings\Interfaces\SettingAttributeInterface;

#[\Attribute]
readonly class JsonSetting implements SettingAttributeInterface
{
    public function __construct(
        private array|\stdClass|\JsonSerializable $default,
        private int $encodeFlags = 0,
        private int $encodeDepth = 512,
        private ?bool $decodeAssociative = null,
        private int $decodeFlags = 0,
        private int $decodeDepth = 512,
    ) {
        //
    }

    public function getDefaultValue(): array|\stdClass|\JsonSerializable
    {
        return $this->default;
    }

    public function isValid(mixed $value): bool
    {
        if (is_array($value)) {
            return true;
        } elseif (!is_object($value)) {
            return false;
        }

        return $value instanceof \stdClass || $value instanceof \JsonSerializable;
    }

    public function toPersisted(string $key, mixed $value, array $settings): array
    {
        return [$key => json_encode($value, $this->encodeFlags, $this->encodeDepth)];
    }

    public function fromPersisted(string $key, mixed $value, array $settings): array
    {
        return [$key => json_decode($value, $this->decodeAssociative, $this->decodeDepth, $this->decodeFlags)];
    }
}
