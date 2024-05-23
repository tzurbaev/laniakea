<?php

declare(strict_types=1);

namespace Laniakea\Forms\Interfaces;

interface FormsManagerInterface
{
    /**
     * Generates form data that will be used in frontend.
     *
     * @param FormInterface $form
     *
     * @return array
     */
    public function getFormData(FormInterface $form): array;
}
