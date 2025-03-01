<?php

declare(strict_types=1);

namespace Laniakea\Transformers;

use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Http\JsonResponse;
use Laniakea\Transformers\Entities\TransformationPayload;
use Laniakea\Transformers\Interfaces\TransformationInterface;
use Laniakea\Transformers\Interfaces\TransformationSerializerInterface;
use Laniakea\Transformers\Interfaces\TransformerResourceInterface;
use Laniakea\Transformers\Resources\CollectionResource;
use Laniakea\Transformers\Resources\ItemResource;

class TransformationManager implements \JsonSerializable
{
    protected int $maxDepth = 10;
    protected array $inclusions = [];
    protected array $exclusions = [];
    protected ?TransformationSerializerInterface $serializer = null;
    protected bool $withDefaultSerializer = true;

    public function __construct(protected readonly mixed $data, protected readonly mixed $transformer)
    {
        //
    }

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
     * Disable default serializer for this transformation.
     *
     * @return $this
     */
    public function withoutDefaultSerializer(): static
    {
        $this->withDefaultSerializer = false;

        return $this;
    }

    /**
     * Set requested inclusions.
     *
     * @param array<string> $inclusions
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
     * @param array<string> $exclusions
     *
     * @return static
     */
    public function parseExclusions(array $exclusions): static
    {
        $this->exclusions = $exclusions;

        return $this;
    }

    /**
     * Create array representation of transformation.
     *
     * @return array<string, mixed>
     */
    public function toArray(): array
    {
        return $this->getTransformation()->toArray();
    }

    /**
     * Specify data which should be serialized to JSON.
     *
     * @return array<string>
     */
    public function jsonSerialize(): array
    {
        return $this->toArray();
    }

    /**
     * Create JSON response.
     *
     * @param int   $status
     * @param array $headers
     * @param int   $encodingOptions
     *
     * @return JsonResponse
     */
    public function respond(int $status = 200, array $headers = [], int $encodingOptions = 0): JsonResponse
    {
        return new JsonResponse($this->toArray(), $status, $headers, $encodingOptions);
    }

    /**
     * Get initial resource transformation.
     *
     * @return TransformationInterface
     */
    public function getTransformation(): TransformationInterface
    {
        $parser = $this->getInclusionsParser();

        return new Transformation(
            resource: $this->createResource(),
            payload: new TransformationPayload(
                maxDepth: $this->maxDepth,
                inclusions: $parser->getRequestedInclusions($this->inclusions),
                exclusions: $parser->getRequestedInclusions($this->exclusions),
                inclusionsParser: $parser,
                serializer: $this->getSerializer(),
            ),
            depth: 0,
        );
    }

    /**
     * Create resource for transformation.
     *
     * @return TransformerResourceInterface
     */
    protected function createResource(): TransformerResourceInterface
    {
        if ($this->data instanceof TransformerResourceInterface) {
            return $this->data;
        } elseif ($this->data instanceof LengthAwarePaginator) {
            return new CollectionResource($this->data->items(), $this->transformer, $this->data);
        } elseif (is_iterable($this->data)) {
            return new CollectionResource($this->data, $this->transformer);
        }

        return new ItemResource($this->data, $this->transformer);
    }

    /**
     * Get serializer for current transformation.
     *
     * @return TransformationSerializerInterface|null
     */
    protected function getSerializer(): ?TransformationSerializerInterface
    {
        if (!is_null($this->serializer)) {
            return $this->serializer;
        } elseif (!$this->withDefaultSerializer) {
            return null;
        }

        $defaultSerializer = config('laniakea.transformers.default_serializer');

        if (empty($defaultSerializer) || !class_exists($defaultSerializer)) {
            return null;
        }

        return new $defaultSerializer();
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
