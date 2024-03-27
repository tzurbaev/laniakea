<?php

declare(strict_types=1);

namespace Laniakea\Settings\Concerns;

use Laniakea\Settings\Interfaces\SettingsDecoratorInterface;

trait CreatesSettingsDecorators
{
    protected ?SettingsDecoratorInterface $settingsDecorator = null;

    /**
     * Make fresh settings decorator or re-use previously created one.
     *
     * @param string|SettingsDecoratorInterface $class
     * @param bool                              $fresh
     *
     * @return SettingsDecoratorInterface
     */
    protected function makeSettingsDecorator(string $class, bool $fresh): SettingsDecoratorInterface
    {
        if (!is_null($this->settingsDecorator) && !$fresh) {
            return $this->settingsDecorator;
        }

        return $this->settingsDecorator = $class::make($this);
    }
}
