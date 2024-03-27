<?php

declare(strict_types=1);

namespace Laniakea\Tests\Workbench\Settings;

use Laniakea\Settings\SettingsDecorator;

class AuthorSettingsDecorator extends SettingsDecorator
{
    public function isEditorial(): bool
    {
        return $this->getValue(AuthorSetting::IS_EDITORIAL);
    }

    public function isAdmin(): bool
    {
        return $this->getValue(AuthorSetting::IS_ADMIN);
    }

    public function areCommentsEnabled(): bool
    {
        return $this->getValue(AuthorSetting::COMMENTS_ENABLED);
    }

    public function getAllowedTypes(): array
    {
        return $this->getValue(AuthorSetting::ALLOWED_TYPES);
    }
}
