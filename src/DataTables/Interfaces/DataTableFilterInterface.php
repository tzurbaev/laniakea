<?php

declare(strict_types=1);

namespace Laniakea\DataTables\Interfaces;

use Illuminate\Http\Request;

interface DataTableFilterInterface
{
    /**
     * Datatable filter type.
     *
     * @return string
     */
    public function getType(): string;

    /**
     * Field name that will be used as query parameter.
     *
     * @return string
     */
    public function getFieldName(): string;

    /**
     * Datatable filter label.
     *
     * @return string|null
     */
    public function getLabel(): ?string;

    /**
     * Current filter value.
     *
     * @param Request $request
     *
     * @return mixed
     */
    public function getCurrentValue(Request $request): mixed;

    /**
     * Additional settings for datatable filter.
     *
     * @return array
     */
    public function getSettings(): array;
}
