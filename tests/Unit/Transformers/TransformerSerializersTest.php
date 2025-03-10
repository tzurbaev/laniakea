<?php

declare(strict_types=1);

use Illuminate\Pagination\LengthAwarePaginator;
use Laniakea\Tests\Workbench\Entities\Book;
use Laniakea\Tests\Workbench\Entities\BookAuthor;
use Laniakea\Tests\Workbench\Transformers\AuthorTransformer;
use Laniakea\Tests\Workbench\Transformers\BookTransformer;
use Laniakea\Tests\Workbench\Transformers\Serializers\CustomDefaultTransformationSerialzier;
use Laniakea\Transformers\Interfaces\TransformationSerializerInterface;
use Laniakea\Transformers\Serializers\ArraySerializer;
use Laniakea\Transformers\Serializers\DataArraySerializer;
use Laniakea\Transformers\TransformationManager;

it('should serialize item with ArraySerializer', function () {
    $book = new Book('The Hobbit', '978-0261102217', new BookAuthor('J.R.R. Tolkien'));
    $manager = new TransformationManager($book, new BookTransformer());
    $manager->setSerializer(new ArraySerializer());
    $transformed = $manager->toArray();

    expect($transformed)->toBe([
        'title' => $book->title,
        'isbn' => $book->isbn,
    ]);
});

it('should serialize collection with ArraySerializer', function () {
    $books = [
        new Book('The Hobbit', '978-0261102217', new BookAuthor('J.R.R. Tolkien')),
        new Book('The Lord of the Rings', '978-0261102385', new BookAuthor('J.R.R. Tolkien')),
        new Book('The Silmarillion', '978-0261102736', new BookAuthor('J.R.R. Tolkien')),
    ];
    $manager = new TransformationManager($books, new BookTransformer());
    $manager->setSerializer(new ArraySerializer());
    $transformed = $manager->toArray();

    expect($transformed)->toBe([
        [
            'title' => $books[0]->title,
            'isbn' => $books[0]->isbn,
        ],
        [
            'title' => $books[1]->title,
            'isbn' => $books[1]->isbn,
        ],
        [
            'title' => $books[2]->title,
            'isbn' => $books[2]->isbn,
        ],
    ]);
});

it('should serialize item with DataArraySerializer', function () {
    $book = new Book('The Hobbit', '978-0261102217', new BookAuthor('J.R.R. Tolkien'));
    $manager = new TransformationManager($book, new BookTransformer());
    $manager->setSerializer(new DataArraySerializer());
    $transformed = $manager->toArray();

    expect($transformed)->toBe([
        'data' => [
            'title' => $book->title,
            'isbn' => $book->isbn,
        ],
    ]);
});

it('should not wrap nested item with data key in DataArraySerializer', function () {
    $book = new Book('The Hobbit', '978-0261102217', new BookAuthor('J.R.R. Tolkien'));
    $manager = new TransformationManager($book, new BookTransformer());
    $manager->setSerializer(new DataArraySerializer());
    $transformed = $manager->parseInclusions(['author'])->toArray();

    expect($transformed)->toBe([
        'data' => [
            'title' => $book->title,
            'isbn' => $book->isbn,
            'author' => [
                'name' => $book->author->name,
            ],
        ],
    ]);
});

it('should serialize collection with DataArraySerializer', function () {
    $books = [
        new Book('The Hobbit', '978-0261102217', new BookAuthor('J.R.R. Tolkien')),
        new Book('The Lord of the Rings', '978-0261102385', new BookAuthor('J.R.R. Tolkien')),
        new Book('The Silmarillion', '978-0261102736', new BookAuthor('J.R.R. Tolkien')),
    ];
    $manager = new TransformationManager($books, new BookTransformer());
    $manager->setSerializer(new DataArraySerializer());
    $transformed = $manager->toArray();

    expect($transformed)->toBe([
        'data' => [
            [
                'title' => $books[0]->title,
                'isbn' => $books[0]->isbn,
            ],
            [
                'title' => $books[1]->title,
                'isbn' => $books[1]->isbn,
            ],
            [
                'title' => $books[2]->title,
                'isbn' => $books[2]->isbn,
            ],
        ],
    ]);
});

it('should not wrap nested collection with data key in DataArraySerializer', function () {
    $books = [
        new Book('The Hobbit', '978-0261102217'),
        new Book('The Lord of the Rings', '978-0261102385'),
        new Book('The Silmarillion', '978-0261102736'),
    ];

    $author = new BookAuthor('J.R.R. Tolkien', $books);
    $manager = new TransformationManager($author, new AuthorTransformer());
    $manager->setSerializer(new DataArraySerializer());
    $transformed = $manager->parseInclusions(['books'])->toArray();

    expect($transformed)->toBe([
        'data' => [
            'name' => $author->name,
            'books' => [
                [
                    'title' => $books[0]->title,
                    'isbn' => $books[0]->isbn,
                ],
                [
                    'title' => $books[1]->title,
                    'isbn' => $books[1]->isbn,
                ],
                [
                    'title' => $books[2]->title,
                    'isbn' => $books[2]->isbn,
                ],
            ],
        ],
    ]);
});

it('should serialize pagination', function (TransformationSerializerInterface $serializer) {
    $books = [
        new Book('The Hobbit', '978-0261102217', new BookAuthor('J.R.R. Tolkien')),
        new Book('The Lord of the Rings', '978-0261102385', new BookAuthor('J.R.R. Tolkien')),
        new Book('The Silmarillion', '978-0261102736', new BookAuthor('J.R.R. Tolkien')),
    ];

    $paginator = new LengthAwarePaginator($books, total: 10, perPage: 3, currentPage: 1);
    $manager = new TransformationManager($paginator, new BookTransformer());
    $manager->setSerializer($serializer);
    $transformed = $manager->toArray();

    expect($transformed['meta'])->toBe([
        'pagination' => [
            'current' => [
                'page' => 1,
                'count' => 3,
            ],
            'total' => [
                'pages' => 4,
                'count' => 10,
            ],
            'per_page' => 3,
            'has_previous_page' => false,
            'has_next_page' => true,
            'elements' => [
                ['type' => 'page', 'page' => 1, 'active' => true],
                ['type' => 'page', 'page' => 2, 'active' => false],
                ['type' => 'page', 'page' => 3, 'active' => false],
                ['type' => 'page', 'page' => 4, 'active' => false],
            ],
        ],
    ]);
})->with([
    ['serializer' => new ArraySerializer()],
    ['serializer' => new DataArraySerializer()],
]);

it('should use default serializer for item transformations', function () {
    config()->set('laniakea.transformers.default_serializer', CustomDefaultTransformationSerialzier::class);
    expect(config('laniakea.transformers.default_serializer'))->toBe(CustomDefaultTransformationSerialzier::class);

    $book = new Book('The Hobbit', '978-0261102217', new BookAuthor('J.R.R. Tolkien'));
    $manager = new TransformationManager($book, new BookTransformer());
    $transformed = $manager->toArray();

    expect($transformed)->toBe([
        'type' => 'item',
        'data' => [
            'title' => $book->title,
            'isbn' => $book->isbn,
        ],
    ]);
});

it('should use default serializer for collection transformations', function () {
    $books = [
        new Book('The Hobbit', '978-0261102217', new BookAuthor('J.R.R. Tolkien')),
        new Book('The Lord of the Rings', '978-0261102385', new BookAuthor('J.R.R. Tolkien')),
        new Book('The Silmarillion', '978-0261102736', new BookAuthor('J.R.R. Tolkien')),
    ];

    config()->set('laniakea.transformers.default_serializer', CustomDefaultTransformationSerialzier::class);
    expect(config('laniakea.transformers.default_serializer'))->toBe(CustomDefaultTransformationSerialzier::class);

    $manager = new TransformationManager($books, new BookTransformer());
    $transformed = $manager->toArray();

    expect($transformed)->toBe([
        'type' => 'collection',
        'data' => [
            [
                'type' => 'nested_item',
                'data' => [
                    'title' => $books[0]->title,
                    'isbn' => $books[0]->isbn,
                ],
            ],
            [
                'type' => 'nested_item',
                'data' => [
                    'title' => $books[1]->title,
                    'isbn' => $books[1]->isbn,
                ],
            ],
            [
                'type' => 'nested_item',
                'data' => [
                    'title' => $books[2]->title,
                    'isbn' => $books[2]->isbn,
                ],
            ],
        ],
    ]);
});

it('should use default serializer for paginatior transformations', function () {
    $books = [
        new Book('The Hobbit', '978-0261102217', new BookAuthor('J.R.R. Tolkien')),
        new Book('The Lord of the Rings', '978-0261102385', new BookAuthor('J.R.R. Tolkien')),
        new Book('The Silmarillion', '978-0261102736', new BookAuthor('J.R.R. Tolkien')),
    ];

    config()->set('laniakea.transformers.default_serializer', CustomDefaultTransformationSerialzier::class);
    expect(config('laniakea.transformers.default_serializer'))->toBe(CustomDefaultTransformationSerialzier::class);

    $paginator = new LengthAwarePaginator($books, total: 10, perPage: 3, currentPage: 1);
    $manager = new TransformationManager($paginator, new BookTransformer());
    $transformed = $manager->toArray();

    expect($transformed)->toBe([
        'type' => 'collection',
        'data' => [
            [
                'type' => 'nested_item',
                'data' => [
                    'title' => $books[0]->title,
                    'isbn' => $books[0]->isbn,
                ],
            ],
            [
                'type' => 'nested_item',
                'data' => [
                    'title' => $books[1]->title,
                    'isbn' => $books[1]->isbn,
                ],
            ],
            [
                'type' => 'nested_item',
                'data' => [
                    'title' => $books[2]->title,
                    'isbn' => $books[2]->isbn,
                ],
            ],
        ],
        'meta' => [
            'type' => 'pagination',
            'data' => [
                'count' => 10,
                'pages' => 4,
            ],
        ],
    ]);
});

it('should use skip default serializer', function () {
    config()->set('laniakea.transformers.default_serializer', CustomDefaultTransformationSerialzier::class);
    expect(config('laniakea.transformers.default_serializer'))->toBe(CustomDefaultTransformationSerialzier::class);

    $book = new Book('The Hobbit', '978-0261102217', new BookAuthor('J.R.R. Tolkien'));
    $manager = new TransformationManager($book, new BookTransformer());
    $transformed = $manager->withoutDefaultSerializer()->toArray();

    expect($transformed)->toBe([
        'title' => $book->title,
        'isbn' => $book->isbn,
    ]);
});
