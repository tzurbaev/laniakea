<?php

declare(strict_types=1);

namespace Laniakea\Settings\Interfaces;

interface SettingInterface
{
    /**
     * Get setting name.
     *
     * @return string|int
     */
    public function getName(): string|int;

    /**
     * Get original setting attribute.
     *
     * @return SettingAttributeInterface
     */
    public function getSettingAttribute(): SettingAttributeInterface;

    /**
     * Get request path for current setting.
     *
     * @return string|null
     */
    public function getRequestPath(): ?string;
}
