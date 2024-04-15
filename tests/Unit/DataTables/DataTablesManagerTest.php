<?php

declare(strict_types=1);

use Illuminate\Http\Request;
use Laniakea\DataTables\Interfaces\DataTablesManagerInterface;
use Laniakea\Tests\Workbench\DataTables\ArticlesDataTable;
use Laniakea\Tests\Workbench\DataTables\ArticlesDataTableWithDefaultSorting;
use Laniakea\Tests\Workbench\DataTables\ArticlesDataTableWithoutPagination;

it('should include table ID', function () {
    /** @var DataTablesManagerInterface $manager */
    $manager = app(DataTablesManagerInterface::class);
    $data = $manager->getDataTableData(new Request(), new ArticlesDataTable());

    expect($data['id'])->toBe('ArticlesDataTable');
});

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

it('should generate current pagination', function (array $query, array $expected) {
    /** @var DataTablesManagerInterface $manager */
    $manager = app(DataTablesManagerInterface::class);
    $data = $manager->getDataTableData(new Request($query), new ArticlesDataTable());

    expect($data['pagination'])->toBe($expected);
})->with([
    ['query' => ['page' => 3], 'expected' => ['enabled' => true, 'page' => 3, 'count' => 15]],
    ['query' => ['page' => 5, 'count' => 50], 'expected' => ['enabled' => true, 'page' => 5, 'count' => 50]],
    ['query' => ['count' => 50], 'expected' => ['enabled' => true, 'page' => 1, 'count' => 50]],
    ['query' => ['filter' => 'value'], 'expected' => ['enabled' => true, 'page' => 1, 'count' => 15]],
]);

it('should should not generate pagination for datatables without pagination', function (array $query) {
    /** @var DataTablesManagerInterface $manager */
    $manager = app(DataTablesManagerInterface::class);
    $data = $manager->getDataTableData(new Request($query), new ArticlesDataTableWithoutPagination());

    expect($data['pagination'])->toBe(['enabled' => false, 'page' => null, 'count' => null]);
})->with([
    ['query' => ['page' => 3], 'expected' => ['enabled' => true, 'page' => 3, 'count' => 15]],
    ['query' => ['page' => 5, 'count' => 50], 'expected' => ['enabled' => true, 'page' => 5, 'count' => 50]],
    ['query' => ['count' => 50], 'expected' => ['enabled' => true, 'page' => 1, 'count' => 50]],
    ['query' => ['filter' => 'value'], 'expected' => ['enabled' => true, 'page' => 1, 'count' => 15]],
]);

it('should generate datatable views', function () {
    /** @var DataTablesManagerInterface $manager */
    $manager = app(DataTablesManagerInterface::class);
    $data = $manager->getDataTableData(new Request(), new ArticlesDataTable());

    expect($data['views'])->toBe([
        [
            'id' => 'all',
            'name' => 'All',
            'settings' => ['system' => true],
        ],
        [
            'id' => 1,
            'name' => 'Only Published',
            'settings' => ['filters' => ['published' => true]],
        ],
    ]);
});

it('should generate datatable settings', function () {
    /** @var DataTablesManagerInterface $manager */
    $manager = app(DataTablesManagerInterface::class);
    $data = $manager->getDataTableData(new Request(), new ArticlesDataTable());

    expect($data['settings'])->toBe([
        'striped' => true,
        'responsive' => false,
    ]);
});
