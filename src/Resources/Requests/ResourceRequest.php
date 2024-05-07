<?php

declare(strict_types=1);

namespace Laniakea\Resources\Requests;

use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Laniakea\Resources\Interfaces\ResourceRequestInterface;

class ResourceRequest implements ResourceRequestInterface
{
    public function __construct(private readonly Request $request)
    {
        //
    }

    protected function getFieldName(string $type, ?string $default = null): string
    {
        return config('laniakea.resources.fields.'.$type, $default ?? $type);
    }

    public function getRequest(): Request
    {
        return $this->request;
    }

    public function getCount(): ?int
    {
        $field = $this->getFieldName('count');

        return $this->request->filled($field) ? intval($this->request->input($field)) : null;
    }

    public function getPage(): ?int
    {
        $field = $this->getFieldName('page');

        return $this->request->filled($field) ? intval($this->request->input($field)) : null;
    }

    public function getInclusions(): array
    {
        $field = $this->getFieldName('inclusions', 'with');
        $inclusions = explode(',', $this->request->input($field) ?? '');

        if (!count($inclusions)) {
            return [];
        }

        return collect($inclusions)
            ->map(fn (string $item) => trim($item))
            ->filter()
            ->unique()
            ->values()
            ->all();
    }

    public function getFilters(array $filters): array
    {
        return $this->request->only($filters);
    }

    public function getSortingColumn(): ?string
    {
        $sorting = $this->getSortingValue();

        if (is_null($sorting)) {
            return null;
        }

        return Str::startsWith($sorting, '-') ? Str::substr($sorting, 1) : $sorting;
    }

    public function getSortingDirection(): ?string
    {
        $sorting = $this->getSortingValue();

        if (is_null($sorting)) {
            return null;
        }

        return Str::startsWith($sorting, '-') ? 'desc' : 'asc';
    }

    protected function getSortingValue(): ?string
    {
        return $this->request->input($this->getFieldName('sorting', 'order_by'));
    }
}
