<?php

declare(strict_types=1);

namespace Laniakea\Settings\Interfaces;

interface HasSettingsDecoratorInterface
{
    /**
     * Get settings decorator.
     *
     * @param bool $fresh
     *
     * @return SettingsDecoratorInterface
     */
    public function getSettingsDecorator(bool $fresh = false): SettingsDecoratorInterface;
}
