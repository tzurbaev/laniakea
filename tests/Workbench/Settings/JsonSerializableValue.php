<?php

declare(strict_types=1);

namespace Laniakea\Tests\Workbench\Settings;

readonly class JsonSerializableValue implements \JsonSerializable
{
    public function __construct(private array $value)
    {
        //
    }

    public function jsonSerialize(): array
    {
        return $this->value;
    }
}
