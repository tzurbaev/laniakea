<?php

declare(strict_types=1);

namespace Laniakea\Forms\Interfaces;

interface FormsManagerInterface
{
    public function getFormData(FormInterface $form): array;
}
