<?php

declare(strict_types=1);

namespace Laniakea\DataTables\Interfaces;

interface DataTableViewInterface
{
    /**
     * Datatable view ID.
     *
     * @return mixed
     */
    public function getId(): mixed;

    /**
     * Datatable view name.
     *
     * @return string
     */
    public function getName(): string;

    /**
     * Additional settings for datatable view.
     *
     * @return array
     */
    public function getSettings(): array;
}
