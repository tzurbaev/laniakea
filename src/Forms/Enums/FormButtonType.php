<?php

declare(strict_types=1);

namespace Laniakea\Forms\Enums;

enum FormButtonType: string
{
    case SUBMIT = 'submit';
    case CANCEL = 'cancel';
    case DESTROY = 'destroy';
    case LINK = 'link';
}
