<?php

declare(strict_types=1);

use Illuminate\Pagination\LengthAwarePaginator;
use Laniakea\Tests\Workbench\Entities\Book;
use Laniakea\Tests\Workbench\Entities\BookAuthor;
use Laniakea\Tests\Workbench\Transformers\AuthorTransformer;
use Laniakea\Tests\Workbench\Transformers\BookTransformer;
use Laniakea\Transformers\Interfaces\TransformationSerializerInterface;
use Laniakea\Transformers\Resources\CollectionResource;
use Laniakea\Transformers\Resources\ItemResource;
use Laniakea\Transformers\Serializers\ArraySerializer;
use Laniakea\Transformers\Serializers\DataArraySerializer;
use Laniakea\Transformers\TransformationManager;

it('should serialize item with ArraySerializer', function () {
    $book = new Book('The Hobbit', '978-0261102217', new BookAuthor('J.R.R. Tolkien'));
    $manager = new TransformationManager();
    $manager->setSerializer(new ArraySerializer());
    $transformed = $manager->getTransformation(new ItemResource($book, new BookTransformer()))
        ->toArray();

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
    $manager = new TransformationManager();
    $manager->setSerializer(new ArraySerializer());
    $transformed = $manager->getTransformation(new CollectionResource($books, new BookTransformer()))
        ->toArray();

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
    $manager = new TransformationManager();
    $manager->setSerializer(new DataArraySerializer());
    $transformed = $manager->getTransformation(new ItemResource($book, new BookTransformer()))
        ->toArray();

    expect($transformed)->toBe([
        'data' => [
            'title' => $book->title,
            'isbn' => $book->isbn,
        ],
    ]);
});

it('should not wrap nested item with data key in DataArraySerializer', function () {
    $book = new Book('The Hobbit', '978-0261102217', new BookAuthor('J.R.R. Tolkien'));
    $manager = new TransformationManager();
    $manager->setSerializer(new DataArraySerializer());
    $transformed = $manager->parseInclusions(['author'])
        ->getTransformation(new ItemResource($book, new BookTransformer()))
        ->toArray();

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
    $manager = new TransformationManager();
    $manager->setSerializer(new DataArraySerializer());
    $transformed = $manager->getTransformation(new CollectionResource($books, new BookTransformer()))
        ->toArray();

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
    $manager = new TransformationManager();
    $manager->setSerializer(new DataArraySerializer());
    $transformed = $manager->parseInclusions(['books'])
        ->getTransformation(new ItemResource($author, new AuthorTransformer()))
        ->toArray();

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
    $manager = new TransformationManager();
    $manager->setSerializer($serializer);
    $transformed = $manager->getTransformation(new CollectionResource($paginator->collect(), new BookTransformer(), $paginator))
        ->toArray();

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
