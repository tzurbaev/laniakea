<?php

declare(strict_types=1);

namespace Laniakea\Repositories\Enums;

enum RepositoryCallbackType: string
{
    // Callback that should be executed before any criteria is applied.
    case BEFORE_CRITERIA = 'before_criteria';

    // Callback that should be executed after all criteria have been applied.
    case AFTER_CRITERIA = 'after_criteria';
}
