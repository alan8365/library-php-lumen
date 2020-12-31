<?php

use App\Models\Book;
use App\Models\User;
use Laravel\Lumen\Testing\DatabaseMigrations;
use Laravel\Lumen\Testing\DatabaseTransactions;

class BookTest extends TestCase
{
    protected $test_isbn = '9789574837939';
    protected $user;
    protected $book_structure = [
        'isbn',
        'title',
        'author',
        'publisher',
        'publication_date',
        'summary',
        'img_src',
        'created_at',
        'updated_at'
    ];
    protected $page_structure = [
        'current_page',
        "first_page_url",
        "from",
        "last_page",
        "last_page_url",
        "next_page_url",
        "path",
        "per_page",
        "prev_page_url",
        "to",
        "total",
    ];

    /**
     * A basic test example.
     *
     * @return void
     */
    public function testList()
    {
        $response = $this->json('GET', '/book', ['perPage' => '1']);

        $response->assertResponseOk();

        $response->seeJsonStructure([
            'data' => array_merge(
                ['data' => [
                    '*' => $this->book_structure
                ]],
                $this->page_structure
            )
        ]);
    }

    public function testDetail()
    {
        $response = $this->json('GET', '/book/' . $this->test_isbn);

        $response->assertResponseOk();

        $response->seeJsonStructure([
            'data' => [
                'detail' => $this->book_structure,
                'isLike',
            ]
        ]);

        $response->seeJson([
            'isLike' => false
        ]);
    }

    public function testSetFavorite()
    {
        $user = factory(User::class)->create();
        $book = factory(Book::class)->create();

        $response = $this->actingAs($user)
            ->json('POST', '/book/favorite/' . $book->isbn);

        $response->assertResponseOk();
    }

    public function testListFavorite()
    {
        $user = factory(User::class)
            ->create();

        $user->books()
            ->save(factory(Book::class)->create());

        $response = $this->actingAs($user)
            ->json('GET', '/book/favorite');

        $response->assertResponseOk();

        $response->seeJsonStructure([
            'data' => array_merge(
                ['data' => [
                    '*' => $this->book_structure
                ]],
                $this->page_structure
            )
        ]);
    }

    //TODO expect case test
}
