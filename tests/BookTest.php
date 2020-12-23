<?php

use Laravel\Lumen\Testing\DatabaseMigrations;
use Laravel\Lumen\Testing\DatabaseTransactions;

class BookTest extends TestCase
{
    /**
     * A basic test example.
     *
     * @return void
     */
    public function testBook()
    {
        $this->json('GET', '/book', ['perPage' => '1'])
            ->seeJson([
                'code' => 200,
            ]);
    }
}
