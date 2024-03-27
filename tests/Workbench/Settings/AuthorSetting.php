<?php

declare(strict_types=1);

namespace Laniakea\Tests\Workbench\Settings;

use Laniakea\Settings\Types\BooleanSetting;
use Laniakea\Settings\Types\EnumArraySetting;
use Laniakea\Settings\Types\NullableStringSetting;
use Laniakea\Tests\Workbench\Settings\Enums\ArticleCategoryType;

enum AuthorSetting: string
{
    #[BooleanSetting(false)]
    case IS_EDITORIAL = 'is_editorial';

    #[BooleanSetting(false)]
    case IS_ADMIN = 'is_admin';

    #[BooleanSetting(true)]
    case COMMENTS_ENABLED = 'comments_enabled';

    #[NullableStringSetting(null)]
    case SIGNATURE = 'signature';

    #[EnumArraySetting([], ArticleCategoryType::class, validateEmpty: true)]
    case ALLOWED_TYPES = 'allowed_types';

    public static function getDefaults(): array
    {
        return [
            self::IS_EDITORIAL->value => false,
            self::IS_ADMIN->value => false,
            self::COMMENTS_ENABLED->value => true,
            self::SIGNATURE->value => null,
            self::ALLOWED_TYPES->value => [],
        ];
    }
}
