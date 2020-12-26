<?php

use App\Models\User;
use Laravel\Lumen\Testing\DatabaseMigrations;
use Laravel\Lumen\Testing\DatabaseTransactions;


class UserTest extends TestCase
{
    /**
     * A basic test example.
     *
     * @return void
     */
    public function testExample()
    {
        $this->json('POST', '/auth/store', ['perPage' => '1'])
            ->seeJson([
                'code' => 400,
            ]);
    }


    /**
     * Test who am i api.
     *
     * @return void
     */
    public function testWhoAmI()
    {
        $user = factory(User::class)->create();
        $response = $this->actingAs($user)
            ->json('GET', 'auth/whoAmI');

        $response->assertResponseOk();

        $response->seeJsonStructure([
            "data" => [
                "email",
                "name",
            ]
        ]);
    }
}
