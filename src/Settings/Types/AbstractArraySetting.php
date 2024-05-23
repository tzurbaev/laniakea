<?php

declare(strict_types=1);

namespace Laniakea\Settings\Types;

abstract readonly class AbstractArraySetting
{
    /**
     * Get list of allowed cases.
     *
     * @return array
     */
    abstract protected function getCases(): array;

    /**
     * Determine if empty array is allowed.
     *
     * @return bool
     */
    abstract protected function validateEmpty(): bool;

    protected function isValidArray(mixed $value, callable $caseChecker): bool
    {
        if (!is_array($value)) {
            return false;
        } elseif (!$this->validateEmpty() && !count($value)) {
            return false;
        }

        $cases = $this->getCases();

        if (count($cases) > 0 && $this->hasInvalidCase($value, $cases, $caseChecker)) {
            return false;
        }

        return true;
    }

    protected function hasInvalidCase(array $value, array $cases, callable $caseChecker): bool
    {
        foreach ($value as $item) {
            if (!in_array($caseChecker($item), $cases)) {
                return true;
            }
        }

        return false;
    }
}
