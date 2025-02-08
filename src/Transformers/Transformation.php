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
    /**
     * Previously parsed inclusions for quick access.
     *
     * @var array
     */
    private array $inclusionsCache = [];

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
        if ($this->resource instanceof ItemResource) {
            return $this->getItem(
                $this->resource->getData(),
                $this->resource->getTransformer(),
                isRoot: $this->depth === 0,
            );
        } elseif ($this->resource instanceof CollectionResource) {
            return $this->getCollection($this->resource);
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

        $paginator = $this->payload->serializer->getPagination($resource->getPaginator());

        $data['meta'] = $paginator;

        return $data;
    }

    /**
     * Get the transformed collection.
     *
     * @param CollectionResource $resource
     *
     * @return array
     */
    protected function getCollection(CollectionResource $resource): array
    {
        $collection = $resource->getData();
        $transformer = $resource->getTransformer();

        $data = [];

        foreach ($collection as $item) {
            $data[] = $this->getItem($item, $transformer, isRoot: false);
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
     *
     * @return array
     */
    protected function getItem(mixed $item, mixed $transformer, bool $isRoot): array
    {
        $data = call_user_func_array([$transformer, 'transform'], [$item]);

        if (!is_array($data)) {
            throw new \RuntimeException('Transformer must return an array.');
        } elseif (count($this->payload->inclusions) > 0) {
            $data = $this->insertInclusions($item, $data, $transformer);
        }

        if (is_null($this->payload->serializer)) {
            return $data;
        } elseif ($isRoot) {
            return $this->payload->serializer->getRootItem($data);
        }

        return $this->payload->serializer->getNestedItem($data);
    }

    /**
     * Add requested inclusions to the data.
     *
     * @param mixed $item
     * @param array $data
     * @param mixed $transformer
     *
     * @return array
     */
    protected function insertInclusions(mixed $item, array $data, mixed $transformer): array
    {
        $availableInclusions = $this->getInclusions($this->payload->inclusionsParser, $transformer);

        if (!count($availableInclusions)) {
            return $data;
        }

        foreach ($availableInclusions as $inclusion) {
            if (!array_key_exists($inclusion->getName(), $this->payload->inclusions)) {
                continue;
            }

            $inclusionResource = call_user_func_array([$transformer, $inclusion->getMethod()], [$item]);

            if (is_null($inclusionResource)) {
                continue;
            } elseif ($inclusionResource instanceof PrimitiveResource) {
                $data[$inclusion->getName()] = $inclusionResource->getData();

                continue;
            }

            $data[$inclusion->getName()] = $this->getNestedTransformation($inclusion, $inclusionResource)->toArray();
        }

        return $data;
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

    /**
     * Get the inclusions for the transformer.
     *
     * @param InclusionsParser $parser
     * @param mixed            $transformer
     *
     * @return array
     */
    protected function getInclusions(InclusionsParser $parser, mixed $transformer): array
    {
        $class = get_class($transformer);

        if (isset($this->inclusionsCache[$class])) {
            return $this->inclusionsCache[$class];
        }

        return $this->inclusionsCache[$class] = $parser->getTransformerInclusions($transformer);
    }
}
