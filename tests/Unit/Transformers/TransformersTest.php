<?php

declare(strict_types=1);

use Illuminate\Http\JsonResponse;
use Laniakea\Tests\Workbench\Entities\Book;
use Laniakea\Tests\Workbench\Entities\BookAuthor;
use Laniakea\Tests\Workbench\Entities\NestedTransformationEntity;
use Laniakea\Tests\Workbench\Transformers\BookTransformer;
use Laniakea\Tests\Workbench\Transformers\BookTransformerWithDefaultAuthorInclusion;
use Laniakea\Tests\Workbench\Transformers\Nested\FirstNestedTransformer;
use Laniakea\Transformers\TransformationManager;

it('should perform basic item transformation', function () {
    $book = new Book('The Hobbit', '978-0261102217', new BookAuthor('J.R.R. Tolkien'));
    $manager = new TransformationManager($book, new BookTransformer());
    $transformed = $manager->toArray();

    expect($transformed)->toBe([
        'title' => $book->title,
        'isbn' => $book->isbn,
    ]);
});

it('should parse item inclusions', function () {
    $book = new Book('The Hobbit', '978-0261102217', new BookAuthor('J.R.R. Tolkien'));
    $manager = new TransformationManager($book, new BookTransformer());
    $transformed = $manager->parseInclusions(['author'])->toArray();

    expect($transformed)->toBe([
        'title' => $book->title,
        'isbn' => $book->isbn,
        'author' => [
            'name' => $book->author->name,
        ],
    ]);
});

it('should perform basic collection transformations', function () {
    $books = [
        new Book('The Hobbit', '978-0261102217', new BookAuthor('J.R.R. Tolkien')),
        new Book('The Lord of the Rings', '978-0261102385', new BookAuthor('J.R.R. Tolkien')),
        new Book('The Silmarillion', '978-0261102736', new BookAuthor('J.R.R. Tolkien')),
    ];

    $manager = new TransformationManager($books, new BookTransformer());
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

it('should parse collection inclusions', function () {
    $books = [
        new Book('The Hobbit', '978-0261102217', new BookAuthor('J.R.R. Tolkien')),
        new Book('The Martian', '978-0804139021', new BookAuthor('Andy Weir')),
        new Book('Harry Potter and the Philosopher\'s Stone', '978-0747532743', new BookAuthor('J.K. Rowling')),
    ];

    $manager = new TransformationManager($books, new BookTransformer());
    $transformed = $manager->parseInclusions(['author'])->toArray();

    expect($transformed)->toBe([
        [
            'title' => $books[0]->title,
            'isbn' => $books[0]->isbn,
            'author' => [
                'name' => $books[0]->author->name,
            ],
        ],
        [
            'title' => $books[1]->title,
            'isbn' => $books[1]->isbn,
            'author' => [
                'name' => $books[1]->author->name,
            ],
        ],
        [
            'title' => $books[2]->title,
            'isbn' => $books[2]->isbn,
            'author' => [
                'name' => $books[2]->author->name,
            ],
        ],
    ]);
});

it('should control max depth', function () {
    $author = new BookAuthor('J.R.R. Tolkien');
    $book = new Book('The Hobbit', '978-0261102217');

    for ($i = 0; $i < 10; $i++) {
        $author->addBook($book);
    }

    $book->setAuthor($author);

    $inclusions = [
        'author.books.author.books.author.books.author.books.author.books.author.books',
    ];

    $manager = new TransformationManager($book, new BookTransformer());
    $manager->setMaxDepth(4);

    $this->expectExceptionMessage('Max depth reached');

    $manager->parseInclusions($inclusions)->toArray();
});

it('should include default inclusions', function () {
    $book = new Book('The Hobbit', '978-0261102217', new BookAuthor('J.R.R. Tolkien'));
    $manager = new TransformationManager($book, new BookTransformerWithDefaultAuthorInclusion());
    $transformed = $manager->toArray();

    expect($transformed)->toBe([
        'title' => $book->title,
        'isbn' => $book->isbn,
        'author' => [
            'name' => $book->author->name,
        ],
    ]);
});

it('should omit default inclusions via exclusions', function () {
    $book = new Book('The Hobbit', '978-0261102217', new BookAuthor('J.R.R. Tolkien'));
    $manager = new TransformationManager($book, new BookTransformerWithDefaultAuthorInclusion());
    $transformed = $manager->parseExclusions(['author'])->toArray();

    expect($transformed)->toBe([
        'title' => $book->title,
        'isbn' => $book->isbn,
    ]);
});

it('should omit only the last inclusion in chain via exclusions', function () {
    $entity = new NestedTransformationEntity(
        'First',
        new NestedTransformationEntity(
            'Second',
            new NestedTransformationEntity('Third')
        ),
    );

    $manager = new TransformationManager($entity, new FirstNestedTransformer());
    $transformed = $manager->parseExclusions(['next.next'])->toArray();

    expect($transformed)->toBe([
        'level' => 1,
        'name' => 'First',
        'next' => [
            'level' => 2,
            'name' => 'Second',
        ],
    ]);
});

it('should create JSON response', function () {
    $book = new Book('The Hobbit', '978-0261102217', new BookAuthor('J.R.R. Tolkien'));
    $manager = new TransformationManager($book, new BookTransformer());
    $response = $manager->parseInclusions(['author'])->respond(201, ['X-Header' => 'Value']);

    expect($response)->toBeInstanceOf(JsonResponse::class)
        ->and($response->status())->toBe(201)
        ->and($response->headers->get('X-Header'))->toBe('Value')
        ->and($response->getOriginalContent())
        ->toBe([
            'title' => $book->title,
            'isbn' => $book->isbn,
            'author' => [
                'name' => $book->author->name,
            ],
        ]);
});
