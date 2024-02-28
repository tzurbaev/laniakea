<?php

declare(strict_types=1);

namespace Laniakea\Forms\Interfaces;

interface FormIdsGeneratorInterface
{
    public function getFormId(): string;

    public function getSectionId(?string $label): string;

    public function getFieldId(string $name, ?string $label): string;
}
