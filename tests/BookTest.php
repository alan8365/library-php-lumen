<?php

use Laravel\Lumen\Testing\DatabaseMigrations;
use Laravel\Lumen\Testing\DatabaseTransactions;

class BookTest extends TestCase
{
    protected $test_isbn = '9789574837939';

    /**
     * A basic test example.
     *
     * @return void
     */
    public function testBook()
    {
        $response = $this->json('GET', '/book', ['perPage' => '1']);

        $response->assertResponseOk();

        $response->seeJsonStructure([
            'data' => [
                'current_page',
                'data' => [
                    '*' => [
                        'isbn',
                        'title',
                        'author',
                        'publisher',
                        'publication_date',
                        'summary',
                        'img_src',
                        'created_at',
                        'updated_at'
                    ]
                ],
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
            ]
        ]);
    }

    public function testBookDetail()
    {
        $response = $this->json('GET', '/book/' . $this->test_isbn);

        $response->assertResponseOk();

        $response->seeJsonStructure([
            'data' => [
                'detail' => [
                    'isbn',
                    'title',
                    'author',
                    'publisher',
                    'publication_date',
                    'summary',
                    'img_src',
                    'created_at',
                    'updated_at'
                ],
                'isLike',
            ]
        ]);

        $response->seeJson([
            'isLike' => false
        ]);
    }
}
