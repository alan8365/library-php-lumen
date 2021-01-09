<?php

use App\Models\User;
use Laravel\Lumen\Testing\DatabaseMigrations;
use Laravel\Lumen\Testing\DatabaseTransactions;
use Faker\Factory;
use Laravel\Lumen\Testing\WithoutMiddleware;

$faker = Factory::create();
$input_data = [
    'name' => $faker->name,
    'email' => $faker->email,
    'password' => $faker->password(8, 32)
];
$auth_token = '';

class UserTest extends TestCase
{
    use WithoutMiddleware;

    /**
     * Test normal store input.
     *
     * @return void
     */
    public function testStore()
    {
        global $input_data;

        $response = $this->json('POST', '/auth/store', $input_data);

        $response->seeJson(['code' => 200]);

        $response->assertResponseOk();

        $response->seeInDatabase('users', ['email' => $input_data['email']]);
    }


    /**
     * Test normal login input.
     *
     * @return void
     */
    public function testLogin()
    {
        global $input_data, $auth_token;

        $response = $this->json('POST', '/auth/login', $input_data);

        $response->seeJson(['code' => 200]);

        $response->seeInDatabase('users', ['name' => $input_data['name']]);

        $auth_token = json_decode($response->response->getContent(), true)['data']['access_token'];
    }

    /**
     * Test normal login input.
     *
     * @return void
     */
    public function testLogout()
    {
        global $auth_token;

        $user = factory(User::class)->create();

        $response = $this->json('POST', '/auth/logout', [], ['Authorization' => 'Bearer '.$auth_token]);

        $response->seeJson(['code' => 200]);

        $response->assertResponseOk();
    }

    /**
     * Test normal whoAmI input.
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
