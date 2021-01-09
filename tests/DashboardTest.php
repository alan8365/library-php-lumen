<?php

use Faker\Factory;
use App\Models\Book;
use App\Models\User;
use Illuminate\Support\Carbon;
use Laravel\Lumen\Testing\DatabaseMigrations;
use Laravel\Lumen\Testing\DatabaseTransactions;


$faker = Factory::create();
$fakeBookData = [
    'isbn' => $faker->isbn13,
    'title' => $faker->realText(100),
    'author' => $faker->firstName . ' ' . $faker->lastName,
    'publisher' => $faker->name(),
    'publication_date' => $faker->date(),
    'summary' => $faker->realText(),
];

class DashboardTest extends TestCase
{
    public function managerUser()
    {
        return User::find('Xiao@gmail.com');
    }


    /**
     * A basic test example.
     *
     * @return void
     */
    public function testGetAllBook()
    {
        $user = $this->managerUser();

        $response = $this->actingAs($user)
            ->get('/dashboard/book');

        $response->seeJson(['code' => 200]);

        $response->seeJsonStructure(['data' => [
            '*' => BookTest::$book_structure
        ]]);
    }


    public function testRemoveBook()
    {
        $book = factory(Book::class)->create();
        $user = $this->managerUser();

        $response = $this->actingAs($user)
            ->json('DELETE', '/dashboard/book/' . $book->isbn);

        $response->seeJson(['code' => 200]);

        $response->notSeeInDatabaseSoftDelete('books', ['isbn' => $book->isbn]);
    }

    public function testStoreBook()
    {
        global $fakeBookData;

        $user = $this->managerUser();

        $response = $this->actingAs($user)
            ->json('POST', '/dashboard/book', $fakeBookData);

        $response->seeJson(['code' => 200]);

        $response->seeInDatabase('books', ['isbn' => $fakeBookData['isbn']]);
    }

    public function testUpdateBook()
    {
        global $fakeBookData;
        global $faker;

        $fakeBookData['publication_date'] = $faker->date();

        $user = $this->managerUser();

        $response = $this->actingAs($user)
            ->json('PUT', '/dashboard/book/' . $fakeBookData['isbn'], $fakeBookData);

        $response->seeJson(['code' => 200]);
    }
}
