<?php

declare(strict_types=1);

namespace Laniakea\Forms\Interfaces;

interface FormSectionInterface
{
    public function getId(): ?string;

    public function getLabel(): ?string;

    public function getDescription(): ?string;

    public function getFieldNames(): array;
}
