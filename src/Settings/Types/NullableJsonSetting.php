<?php

declare(strict_types=1);

namespace Laniakea\Settings\Types;

use Laniakea\Settings\Interfaces\SettingAttributeInterface;

#[\Attribute]
readonly class NullableJsonSetting implements SettingAttributeInterface
{
    public function __construct(
        private array|\stdClass|\JsonSerializable|null $default,
        private int $encodeFlags = 0,
        private int $encodeDepth = 512,
        private ?bool $decodeAssociative = null,
        private int $decodeFlags = 0,
        private int $decodeDepth = 512,
    ) {
        //
    }

    public function getDefaultValue(): array|\stdClass|\JsonSerializable|null
    {
        return $this->default;
    }

    public function isValid(mixed $value): bool
    {
        if (is_null($value)) {
            return true;
        } elseif (is_array($value)) {
            return true;
        } elseif (!is_object($value)) {
            return false;
        }

        return $value instanceof \stdClass || $value instanceof \JsonSerializable;
    }

    public function toPersisted(string $key, mixed $value, array $settings): array
    {
        return [$key => is_null($value) ? null : json_encode($value, $this->encodeFlags, $this->encodeDepth)];
    }

    public function fromPersisted(string $key, mixed $value, array $settings): array
    {
        return [$key => is_null($value) ? null : json_decode($value, $this->decodeAssociative, $this->decodeDepth, $this->decodeFlags)];
    }
}
