<?php

declare(strict_types=1);

namespace Laniakea\Settings;

use Laniakea\Settings\Interfaces\SettingAttributeInterface;
use Laniakea\Settings\Interfaces\SettingInterface;

readonly class Setting implements SettingInterface
{
    public function __construct(
        private \BackedEnum $name,
        private SettingAttributeInterface $settingAttribute,
        private ?string $requestPath = null,
    ) {
        //
    }

    public function getName(): string|int
    {
        return $this->name->value;
    }

    public function getSettingAttribute(): SettingAttributeInterface
    {
        return $this->settingAttribute;
    }

    public function getRequestPath(): ?string
    {
        return $this->requestPath;
    }
}
