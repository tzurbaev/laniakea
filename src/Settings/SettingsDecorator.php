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

    /**
     * Create fresh settings values from the model.
     *
     * @return array
     */
    protected function getFreshSettings(): array
    {
        return $this->values->fromPersisted(
            $this->model->getSettingsEnum(),
            $this->model->getCurrentSettings() ?? [],
        );
    }

    /**
     * Ensure settings are initialized.
     */
    protected function guardSettings(): void
    {
        if (is_null($this->settings)) {
            $this->settings = $this->getFreshSettings();
        }
    }

    /**
     * Get value for the given setting.
     *
     * @param \BackedEnum|string $name
     * @param mixed|null         $default
     *
     * @return mixed
     */
    public function getValue(\BackedEnum|string $name, mixed $default = null): mixed
    {
        $this->guardSettings();

        $key = $name instanceof \BackedEnum ? $name->value : $name;

        return array_key_exists($key, $this->settings) ? $this->settings[$key] : $default;
    }

    /**
     * Get all settings values.
     *
     * @return array
     */
    public function getSettings(): array
    {
        $this->guardSettings();

        return $this->settings;
    }

    /**
     * Update settings with given values.
     * Use $ignoreRequestPaths to skip request path checks (or use `fill` method).
     *
     * @param array $settings
     * @param bool  $ignoreRequestPaths
     *
     * @return $this
     */
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

    /**
     * Update settings with given values and ignore request paths.
     *
     * @param array $settings
     *
     * @return $this
     */
    public function fill(array $settings): static
    {
        return $this->update($settings, ignoreRequestPaths: true);
    }
}
