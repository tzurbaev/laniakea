<?php

declare(strict_types=1);

namespace Laniakea\Transformers;

use Laniakea\Transformers\Attributes\Inclusion;
use Laniakea\Transformers\Entities\TransformerInclusion;

class InclusionsParser
{
    /**
     * Previously parsed inclusions for quick access.
     *
     * @var array
     */
    protected array $inclusionsCache = [];

    /**
     * Parse transformer inclusions.
     *
     * @param mixed $transformer
     *
     * @return array|TransformerInclusion[]
     */
    public function getTransformerInclusions(mixed $transformer): array
    {
        $transformerClass = is_string($transformer) ? $transformer : get_class($transformer);

        if (array_key_exists($transformerClass, $this->inclusionsCache)) {
            return $this->inclusionsCache[$transformerClass];
        }

        $methods = (new \ReflectionClass($transformer))->getMethods(\ReflectionMethod::IS_PUBLIC);

        if (!count($methods)) {
            return $this->inclusionsCache[$transformerClass] = [];
        }

        $inclusions = [];

        foreach ($methods as $method) {
            $attributes = $method->getAttributes(Inclusion::class, \ReflectionAttribute::IS_INSTANCEOF);

            if (!count($attributes)) {
                continue;
            }

            /** @var Inclusion $inclusion */
            $inclusion = $attributes[0]->newInstance();

            $inclusions[] = new TransformerInclusion(
                name: $inclusion->getName(),
                default: $inclusion->isDefault(),
                method: $method->getName(),
            );
        }

        return $this->inclusionsCache[$transformerClass] = $inclusions;
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
