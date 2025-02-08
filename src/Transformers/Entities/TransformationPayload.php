<?php

declare(strict_types=1);

namespace Laniakea\Transformers\Entities;

use Laniakea\Transformers\InclusionsParser;
use Laniakea\Transformers\Interfaces\TransformationSerializerInterface;

readonly class TransformationPayload
{
    public function __construct(
        public int $maxDepth,
        public array $inclusions,
        public InclusionsParser $inclusionsParser,
        public ?TransformationSerializerInterface $serializer = null,
    ) {
        //
    }

    /**
     * Create payload for nested inclusion.
     *
     * @param TransformerInclusion $inclusion
     *
     * @return static
     */
    public function getNestedPayload(TransformerInclusion $inclusion): static
    {
        return new static(
            maxDepth: $this->maxDepth,
            inclusions: $this->inclusions[$inclusion->getName()] ?? [],
            inclusionsParser: $this->inclusionsParser,
            serializer: $this->serializer,
        );
    }
}
