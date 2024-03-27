<?php

declare(strict_types=1);

namespace Laniakea\Tests\Workbench\Settings\Enums;

enum ArticleCategoryType: string
{
    case NEWS = 'news';
    case BLOG = 'blog';
    case VIDEO = 'video';
    case OTHER = 'other';
}
