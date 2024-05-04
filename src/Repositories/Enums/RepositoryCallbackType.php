<?php

declare(strict_types=1);

namespace Laniakea\Repositories\Enums;

enum RepositoryCallbackType: string
{
    case BEFORE_CRITERIA = 'before_criteria';
    case AFTER_CRITERIA = 'after_criteria';
}
