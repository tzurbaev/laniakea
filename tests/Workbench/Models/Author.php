<?php

declare(strict_types=1);

namespace Laniakea\Tests\Workbench\Models;

use Illuminate\Database\Eloquent\Model;
use Laniakea\Settings\Concerns\CreatesSettingsDecorators;
use Laniakea\Settings\Interfaces\HasSettingsDecoratorInterface;
use Laniakea\Settings\Interfaces\HasSettingsInterface;
use Laniakea\Tests\Workbench\Settings\AuthorSetting;
use Laniakea\Tests\Workbench\Settings\AuthorSettingsDecorator;

/**
 * @property int $id
 * @property string $name
 * @property array $settings
 */
class Author extends Model implements HasSettingsInterface, HasSettingsDecoratorInterface
{
    use CreatesSettingsDecorators;

    protected $fillable = ['name', 'settings'];
    protected $casts = [
        'settings' => 'json',
    ];

    public function getSettingsEnum(): string
    {
        return AuthorSetting::class;
    }

    public function getCurrentSettings(): ?array
    {
        return $this->settings;
    }

    public function updateSettings(array $settings): void
    {
        $this->update(['settings' => $settings]);
    }

    public function getSettingsDecorator(bool $fresh = false): AuthorSettingsDecorator
    {
        return $this->makeSettingsDecorator(AuthorSettingsDecorator::class, $fresh);
    }
}
