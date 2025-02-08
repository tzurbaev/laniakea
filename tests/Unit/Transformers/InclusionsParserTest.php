<?php

declare(strict_types=1);

use Laniakea\Tests\Workbench\Transformers\BookTransformer;
use Laniakea\Transformers\Entities\TransformerInclusion;
use Laniakea\Transformers\InclusionsParser;

it('should parse transformer inclusions', function (mixed $transformer) {
    $inclusions = (new InclusionsParser())->getTransformerInclusions($transformer);

    expect($inclusions)->toBeArray()
        ->and($inclusions)->toHaveCount(1)
        ->and($inclusions[0])->toBeInstanceOf(TransformerInclusion::class)
        ->and($inclusions[0]->getName())->toBe('author')
        ->and($inclusions[0]->isDefault())->toBeFalse()
        ->and($inclusions[0]->getMethod())->toBe('getAuthor');
})->with([
    ['transformer' => new BookTransformer()],
    ['transformer' => BookTransformer::class],
]);

it('should parse requested inclusions', function (array $requested, array $expected) {
    $inclusions = (new InclusionsParser())->getRequestedInclusions($requested);

    expect($inclusions)->toBe($expected);
})->with([
    [
        'requested' => [],
        'expected' => [],
    ],
    [
        'requested' => ['author'],
        'expected' => ['author' => []],
    ],
    [
        'requested' => ['author', 'publisher'],
        'expected' => ['author' => [], 'publisher' => []],
    ],
    [
        'requested' => ['author', 'publisher', 'author'],
        'expected' => ['author' => [], 'publisher' => []],
    ],
    [
        'requested' => ['author.country', 'publisher', 'author'],
        'expected' => ['author' => [ 'country' => [] ], 'publisher' => []],
    ],
    [
        'requested' => ['author..country', 'publisher', 'author'],
        'expected' => ['author' => [ 'country' => [] ], 'publisher' => []],
    ],
    [
        'requested' => [
            'product', 'product.images', 'product.variants.prices', 'product.variants.images',
            'product.categories.seo', 'product.categories.cover',
        ],
        'expected' => [
            'product' => [
                'images' => [],
                'variants' => [
                    'prices' => [],
                    'images' => [],
                ],
                'categories' => [
                    'seo' => [],
                    'cover' => [],
                ],
            ],
        ],
    ],
]);
