<?php

declare(strict_types=1);

use Illuminate\Http\Request;
use Laniakea\DataTables\Interfaces\DataTablesManagerInterface;
use Laniakea\Tests\Workbench\DataTables\ArticlesDataTable;
use Laniakea\Tests\Workbench\DataTables\ArticlesDataTableWithDefaultSorting;

it('should generate api definition', function () {
    /** @var DataTablesManagerInterface $manager */
    $manager = app(DataTablesManagerInterface::class);
    $data = $manager->getDataTableData(new Request(), new ArticlesDataTable());

    expect($data['api'])->toBe([
        'method' => 'POST',
        'url' => '/articles',
        'headers' => ['Accept' => 'application/json', 'X-Custom' => 'custom'],
        'data_path' => 'data.items',
    ]);
});

it('should generate columns', function () {
    /** @var DataTablesManagerInterface $manager */
    $manager = app(DataTablesManagerInterface::class);
    $data = $manager->getDataTableData(new Request(), new ArticlesDataTable());

    expect($data['columns'])->toBe([
        [
            'type' => 'IDColumn',
            'label' => 'ID',
            'sorting' => ['column' => 'id', 'type' => 'numerical'],
            'settings' => ['show_copy_button' => true],
        ],
        [
            'type' => 'ArticleTitleColumn',
            'label' => 'Article',
            'sorting' => ['column' => 'title', 'type' => 'alphabetical'],
            'settings' => ['preview_length' => 250],
        ],
        [
            'type' => 'DateTimeColumn',
            'label' => 'Published At',
            'sorting' => ['column' => 'created_at', 'type' => 'numerical'],
            'settings' => ['column' => 'created_at', 'format' => "LLLL do, yyyy 'at' HH:mm"],
        ],
    ]);
});

it('should generate filters', function () {
    /** @var DataTablesManagerInterface $manager */
    $manager = app(DataTablesManagerInterface::class);
    $data = $manager->getDataTableData(new Request(), new ArticlesDataTable());

    expect($data['filters'])->toBe([
        [
            'type' => 'SelectField',
            'field_name' => 'count',
            'label' => 'Items Count',
            'value' => 15,
            'settings' => [],
        ],
    ]);
});

it('should generate filters with current values', function () {
    /** @var DataTablesManagerInterface $manager */
    $manager = app(DataTablesManagerInterface::class);
    $data = $manager->getDataTableData(new Request(['count' => '25']), new ArticlesDataTable());

    expect($data['filters'])->toBe([
        [
            'type' => 'SelectField',
            'field_name' => 'count',
            'label' => 'Items Count',
            'value' => 25,
            'settings' => [],
        ],
    ]);
});

it('should generate current datatable sorting', function (array $query, ?array $expected) {
    /** @var DataTablesManagerInterface $manager */
    $manager = app(DataTablesManagerInterface::class);
    $data = $manager->getDataTableData(new Request($query), new ArticlesDataTable());

    expect($data['sorting'])->toBe($expected);
})->with([
    ['query' => ['order_by' => 'title'], 'expected' => ['column' => 'title', 'direction' => 'asc']],
    ['query' => ['order_by' => '-title'], 'expected' => ['column' => 'title', 'direction' => 'desc']],
    ['query' => ['filter' => 'value'], 'expected' => null],
    ['query' => [], 'expected' => null],
]);

it('should generate current datatable sorting and fallback to default sorting', function (array $query, array $expected) {
    /** @var DataTablesManagerInterface $manager */
    $manager = app(DataTablesManagerInterface::class);
    $data = $manager->getDataTableData(new Request($query), new ArticlesDataTableWithDefaultSorting());

    expect($data['sorting'])->toBe($expected);
})->with([
    ['query' => ['order_by' => 'title'], 'expected' => ['column' => 'title', 'direction' => 'asc']],
    ['query' => ['order_by' => '-title'], 'expected' => ['column' => 'title', 'direction' => 'desc']],
    ['query' => ['filter' => 'value'], 'expected' => ['column' => 'created_at', 'direction' => 'desc']],
    ['query' => [], 'expected' => ['column' => 'created_at', 'direction' => 'desc']],
]);
