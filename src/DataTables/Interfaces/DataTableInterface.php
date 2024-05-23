<?php

declare(strict_types=1);

namespace Laniakea\DataTables\Interfaces;

interface DataTableInterface
{
    /**
     * Datatable ID.
     *
     * @return mixed
     */
    public function getId(): mixed;

    /**
     * HTTP method.
     *
     * @return string
     */
    public function getMethod(): string;

    /**
     * API URL.
     *
     * @return string
     */
    public function getUrl(): string;

    /**
     * HTTP headers that should be sent to the API URL.
     *
     * @return array
     */
    public function getHeaders(): array;

    /**
     * Data path in the API response.
     *
     * @return string|null
     */
    public function getDataPath(): ?string;

    /**
     * Datatable columns list.
     *
     * @return array|DataTableColumnInterface[]
     */
    public function getColumns(): array;

    /**
     * Datatable filters list.
     *
     * @return array|DataTableFilterInterface[]
     */
    public function getFilters(): array;

    /**
     * Datatable views list.
     *
     * @return array|DataTableViewInterface[]
     */
    public function getViews(): array;

    /**
     * Additional settings for datatable.
     *
     * @return array
     */
    public function getSettings(): array;
}
