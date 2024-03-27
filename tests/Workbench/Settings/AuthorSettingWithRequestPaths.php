<?php

declare(strict_types=1);

namespace Laniakea\Tests\Workbench\Settings;

use Laniakea\Settings\RequestPath;
use Laniakea\Settings\Types\BooleanSetting;
use Laniakea\Settings\Types\EnumArraySetting;
use Laniakea\Settings\Types\NullableStringSetting;
use Laniakea\Tests\Workbench\Settings\Enums\ArticleCategoryType;

enum AuthorSettingWithRequestPaths: string
{
    #[BooleanSetting(false)]
    #[RequestPath('roles.is_editorial')]
    case IS_EDITORIAL = 'is_editorial';

    #[BooleanSetting(false)]
    #[RequestPath('roles.is_admin')]
    case IS_ADMIN = 'is_admin';

    #[BooleanSetting(true)]
    #[RequestPath('comments.enabled')]
    case COMMENTS_ENABLED = 'comments_enabled';

    #[NullableStringSetting(null)]
    #[RequestPath('misc.signature')]
    case SIGNATURE = 'signature';

    #[EnumArraySetting([], ArticleCategoryType::class, validateEmpty: true)]
    #[RequestPath('misc.category_types')]
    case ALLOWED_TYPES = 'allowed_types';
}
