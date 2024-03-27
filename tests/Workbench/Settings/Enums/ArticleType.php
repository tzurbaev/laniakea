<?php

declare(strict_types=1);

namespace Laniakea\Tests\Workbench\Settings\Enums;

enum ArticleType: string
{
    case EDITORIAL = 'editorial';
    case UGC = 'ugc';
}
