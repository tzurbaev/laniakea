<?php

declare(strict_types=1);

namespace Laniakea\Tests;

use Laniakea\Settings\Interfaces\SettingsValuesInterface;
use Laniakea\Settings\SettingsGenerator;
use Laniakea\Settings\SettingsValues;

trait CreatesSettingsValues
{
    public function createSettingsValues(): SettingsValuesInterface
    {
        return new SettingsValues(new SettingsGenerator());
    }
}
