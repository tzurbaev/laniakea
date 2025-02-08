<?php

declare(strict_types=1);

namespace Laniakea\Transformers;

use Laniakea\Transformers\Attributes\Inclusion;
use Laniakea\Transformers\Entities\TransformerInclusion;

class InclusionsParser
{
    /**
     * Parse transformer inclusions.
     *
     * @param mixed $transformer
     *
     * @return array|TransformerInclusion[]
     */
    public function getTransformerInclusions(mixed $transformer): array
    {
        $methods = (new \ReflectionClass($transformer))->getMethods(\ReflectionMethod::IS_PUBLIC);

        if (!count($methods)) {
            return [];
        }

        $inclusions = array_map(function (\ReflectionMethod $method) {
            $attributes = $method->getAttributes(Inclusion::class, \ReflectionAttribute::IS_INSTANCEOF);

            if (!count($attributes)) {
                return null;
            }

            /** @var Inclusion $inclusion */
            $inclusion = $attributes[0]->newInstance();

            return new TransformerInclusion(
                name: $inclusion->getName(),
                default: $inclusion->isDefault(),
                method: $method->getName(),
            );
        }, $methods);

        return array_values(array_filter($inclusions));
    }

    /**
     * Parse requested inclusions into a tree.
     *
     * @param array $inclusions
     *
     * @return array
     */
    public function getRequestedInclusions(array $inclusions): array
    {
        $result = [];

        foreach ($inclusions as $path) {
            $levels = explode('.', $path);
            $filteredLevels = [];

            foreach ($levels as $level) {
                if ($level !== '') {
                    $filteredLevels[] = $level;
                }
            }

            $current = &$result;

            foreach ($filteredLevels as $level) {
                if (!isset($current[$level])) {
                    $current[$level] = [];
                }

                $current = &$current[$level];
            }
        }

        return $result;
    }
}
