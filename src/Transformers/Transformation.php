<?php

declare(strict_types=1);

namespace Laniakea\Transformers;

use Laniakea\Transformers\Entities\TransformationPayload;
use Laniakea\Transformers\Entities\TransformerInclusion;
use Laniakea\Transformers\Interfaces\TransformationInterface;
use Laniakea\Transformers\Interfaces\TransformerResourceInterface;
use Laniakea\Transformers\Resources\CollectionResource;
use Laniakea\Transformers\Resources\ItemResource;
use Laniakea\Transformers\Resources\PrimitiveResource;

class Transformation implements TransformationInterface
{
    public function __construct(
        protected TransformerResourceInterface $resource,
        protected TransformationPayload $payload,
        protected int $depth,
    ) {
        //
    }

    /**
     * Get the transformed data.
     *
     * @return array
     */
    public function toArray(): array
    {
        if ($this->depth > $this->payload->maxDepth) {
            throw new \RuntimeException('Max depth reached.');
        }

        $data = $this->getTransformedData();

        if ($this->depth > 0) {
            return $data;
        }

        return $this->getSerializedData($this->resource, $data);
    }

    /**
     * Get transformed and serialized resource data.
     *
     * @return array
     */
    protected function getTransformedData(): array
    {
        $inclusions = $this->getRequestedInclusions();

        if ($this->resource instanceof ItemResource) {
            return $this->getItem(
                $this->resource->getData(),
                $this->resource->getTransformer(),
                isRoot: $this->depth === 0,
                requestedInclusions: $inclusions,
            );
        } elseif ($this->resource instanceof CollectionResource) {
            return $this->getCollection($this->resource, $inclusions);
        }

        throw new \RuntimeException('Resource type ['.get_class($this->resource).'] is not supported.');
    }

    /**
     * Get final serialzied data.
     *
     * @param TransformerResourceInterface $resource
     * @param array                        $data
     *
     * @return array
     */
    protected function getSerializedData(TransformerResourceInterface $resource, array $data): array
    {
        if (is_null($this->payload->serializer)) {
            return $data;
        } elseif (!($resource instanceof CollectionResource)) {
            return $data;
        } elseif (!$resource->hasPaginator()) {
            return $data;
        }

        $data['meta'] = $this->payload->serializer->getPagination($resource->getPaginator());

        return $data;
    }

    /**
     * Get the transformed collection.
     *
     * @param CollectionResource $resource
     * @param array              $inclusions
     *
     * @return array
     */
    protected function getCollection(CollectionResource $resource, array $inclusions): array
    {
        $transformer = $resource->getTransformer();

        $data = [];

        foreach ($resource->getData() as $item) {
            $data[] = $this->getItem($item, $transformer, isRoot: false, requestedInclusions: $inclusions);
        }

        if (is_null($this->payload->serializer)) {
            return $data;
        } elseif ($this->depth > 0) {
            return $this->payload->serializer->getNestedCollection($data);
        }

        return $this->payload->serializer->getRootCollection($data);
    }

    /**
     * Get the transformed item.
     *
     * @param mixed $item
     * @param mixed $transformer
     * @param bool  $isRoot
     * @param array $requestedInclusions
     *
     * @return array
     */
    protected function getItem(mixed $item, mixed $transformer, bool $isRoot, array $requestedInclusions): array
    {
        $data = $transformer->transform($item);

        if (!is_array($data)) {
            throw new \RuntimeException('Transformer must return an array.');
        }

        foreach ($requestedInclusions as $inclusion) {
            $method = $inclusion->getMethod();
            $inclusionResource = $transformer->$method($item);

            if (is_null($inclusionResource)) {
                continue;
            } elseif ($inclusionResource instanceof PrimitiveResource) {
                $data[$inclusion->getName()] = $inclusionResource->getData();

                continue;
            }

            $data[$inclusion->getName()] = $this->getNestedTransformation($inclusion, $inclusionResource)->toArray();
        }

        if (is_null($this->payload->serializer)) {
            return $data;
        } elseif ($isRoot) {
            return $this->payload->serializer->getRootItem($data);
        }

        return $this->payload->serializer->getNestedItem($data);
    }

    /**
     * Extract default and requested inclusions.
     *
     * @return array
     */
    protected function getRequestedInclusions(): array
    {
        $available = $this->payload->inclusionsParser->getTransformerInclusions($this->resource->getTransformer());

        if (!count($available)) {
            return [];
        }

        $inclusions = [];

        foreach ($available as $inclusion) {
            if (!isset($this->payload->inclusions[$inclusion->getName()]) && !$inclusion->isDefault()) {
                // Inclusion was not requested and it is not a default one.
                continue;
            } elseif (($this->payload->exclusions[$inclusion->getName()] ?? null) === []) {
                // Default inclusion was excluded and it has no nested inclusions.
                continue;
            }

            $inclusions[] = $inclusion;
        }

        return $inclusions;
    }

    /**
     * Create nested transformation for the given inclusion.
     *
     * @param TransformerInclusion         $inclusion
     * @param TransformerResourceInterface $resource
     *
     * @return static
     */
    protected function getNestedTransformation(
        TransformerInclusion $inclusion,
        TransformerResourceInterface $resource,
    ): static {
        return new static(
            resource: $resource,
            payload: $this->payload->getNestedPayload($inclusion),
            depth: $this->depth + 1,
        );
    }
}
