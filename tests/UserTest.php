<?php

use App\Models\User;
use Laravel\Lumen\Testing\DatabaseMigrations;
use Laravel\Lumen\Testing\DatabaseTransactions;
use Faker\Factory;

class UserTest extends TestCase
{
    /**
     * A basic test example.
     *
     * @return void
     */
    public function testStore()
    {
        $faker = Factory::create();
        $input_data = [
            'name' => $faker->name,
            'email' => $faker->email,
            'password' => $faker->password
        ];

        $response = $this->json('POST', '/auth/store', $input_data);
        $response->assertResponseOk();

        $response->seeInDatabase('users', ['name' => $input_data['name']]);
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
