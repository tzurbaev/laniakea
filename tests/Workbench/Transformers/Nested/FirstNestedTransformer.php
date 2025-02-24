<?php

declare(strict_types=1);

namespace Laniakea\Tests\Workbench\Transformers\Nested;

use Laniakea\Tests\Workbench\Entities\NestedTransformationEntity;
use Laniakea\Transformers\AbstractTransformer;
use Laniakea\Transformers\Attributes\Inclusion;
use Laniakea\Transformers\Interfaces\TransformerResourceInterface;

class FirstNestedTransformer extends AbstractTransformer
{
    public function transform(NestedTransformationEntity $entity): array
    {
        return [
            'level' => 1,
            'name' => $entity->name,
        ];
    }

    #[Inclusion('next', true)]
    public function getNext(NestedTransformationEntity $entity): TransformerResourceInterface
    {
        if (is_null($entity->next)) {
            return $this->primitive(null);
        }

        return $this->item($entity->next, new SecondNestedTransformer());
    }
}
