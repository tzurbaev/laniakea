<?php

declare(strict_types=1);

namespace Laniakea\Settings;

use Illuminate\Container\Container;
use Laniakea\Settings\Interfaces\HasSettingsInterface;
use Laniakea\Settings\Interfaces\SettingsDecoratorInterface;
use Laniakea\Settings\Interfaces\SettingsUpdaterInterface;
use Laniakea\Settings\Interfaces\SettingsValuesInterface;

class SettingsDecorator implements SettingsDecoratorInterface
{
    protected ?array $settings = null;

    public function __construct(
        protected readonly SettingsValuesInterface $values,
        protected readonly SettingsUpdaterInterface $updater,
        protected readonly HasSettingsInterface $model,
    ) {
        //
    }

    /**
     * Create fresh settings decorator for given model.
     *
     * @param HasSettingsInterface $model
     *
     * @return static
     */
    public static function make(HasSettingsInterface $model): static
    {
        return Container::getInstance()->make(static::class, ['model' => $model]);
    }

    protected function getFreshSettings(): array
    {
        return $this->values->fromPersisted(
            $this->model->getSettingsEnum(),
            $this->model->getCurrentSettings() ?? [],
        );
    }

    protected function guardSettings(): void
    {
        if (is_null($this->settings)) {
            $this->settings = $this->getFreshSettings();
        }
    }

    public function getValue(\BackedEnum|string $name, mixed $default = null): mixed
    {
        $this->guardSettings();

        $key = $name instanceof \BackedEnum ? $name->value : $name;

        return array_key_exists($key, $this->settings) ? $this->settings[$key] : $default;
    }

    public function getSettings(): array
    {
        $this->guardSettings();

        return $this->settings;
    }

    public function update(array $settings, bool $ignoreRequestPaths = false): static
    {
        $updated = $this->updater->update(
            $this->model,
            $settings,
            $ignoreRequestPaths,
        );

        $this->settings = $this->values->fromPersisted($this->model->getSettingsEnum(), $updated);

        return $this;
    }

    public function fill(array $settings): static
    {
        return $this->update($settings, ignoreRequestPaths: true);
    }
}
