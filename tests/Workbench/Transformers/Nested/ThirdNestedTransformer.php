<?php

declare(strict_types=1);

namespace Laniakea\Tests\Workbench\Transformers\Nested;

use Laniakea\Tests\Workbench\Entities\NestedTransformationEntity;

class ThirdNestedTransformer
{
    public function transform(NestedTransformationEntity $entity): array
    {
        return [
            'level' => 3,
            'name' => $entity->name,
        ];
    }
}
