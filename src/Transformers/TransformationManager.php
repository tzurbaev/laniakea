<?php

declare(strict_types=1);

namespace Laniakea\Transformers;

use Laniakea\Transformers\Entities\TransformationPayload;
use Laniakea\Transformers\Interfaces\TransformationInterface;
use Laniakea\Transformers\Interfaces\TransformationSerializerInterface;
use Laniakea\Transformers\Interfaces\TransformerResourceInterface;

class TransformationManager
{
    protected int $maxDepth = 10;
    protected array $inclusions = [];
    protected array $exclusions = [];
    protected ?TransformationSerializerInterface $serializer = null;

    /**
     * Set max nesting depth.
     *
     * @param int $maxDepth
     *
     * @return static
     */
    public function setMaxDepth(int $maxDepth): static
    {
        $this->maxDepth = $maxDepth;

        return $this;
    }

    /**
     * Set transformation serializer.
     *
     * @param TransformationSerializerInterface $serializer
     *
     * @return static
     */
    public function setSerializer(TransformationSerializerInterface $serializer): static
    {
        $this->serializer = $serializer;

        return $this;
    }

    /**
     * Set requested inclusions.
     *
     * @param array $inclusions
     *
     * @return static
     */
    public function parseInclusions(array $inclusions): static
    {
        $this->inclusions = $inclusions;

        return $this;
    }

    /**
     * Set requested exclusions.
     *
     * @param array $exclusions
     *
     * @return static
     */
    public function parseExclusions(array $exclusions): static
    {
        $this->exclusions = $exclusions;

        return $this;
    }

    /**
     * Get initial resource transformation.
     *
     * @param TransformerResourceInterface $resource
     *
     * @return TransformationInterface
     */
    public function getTransformation(TransformerResourceInterface $resource): TransformationInterface
    {
        $parser = $this->getInclusionsParser();

        return new Transformation(
            resource: $resource,
            payload: new TransformationPayload(
                maxDepth: $this->maxDepth,
                inclusions: $parser->getRequestedInclusions($this->inclusions),
                exclusions: $parser->getRequestedInclusions($this->exclusions),
                inclusionsParser: $parser,
                serializer: $this->serializer,
            ),
            depth: 0,
        );
    }

    /**
     * Get fresh inclusions parser.
     *
     * @return InclusionsParser
     */
    protected function getInclusionsParser(): InclusionsParser
    {
        return new InclusionsParser();
    }
}
