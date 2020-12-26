<?php

/** @var Factory $factory */

use App\Models\Book;
use Faker\Generator as Faker;
use Illuminate\Database\Eloquent\Factory;
use Illuminate\Support\Carbon;
use Illuminate\Support\Str;


/*
|--------------------------------------------------------------------------
| Model Factories
|--------------------------------------------------------------------------
|
| This directory should contain each of the model factory definitions for
| your application. Factories provide a convenient way to generate new
| model instances for testing / seeding your application's database.
|
*/

$factory->define(Book::class, function (Faker $faker) {
    return [
        'isbn' => $faker->isbn13,
        'title' => $faker->realText(100),
        'author' => $faker->firstName . ' ' . $faker->lastName,
        'publisher' => $faker->name(),
        'publication_date' => $faker->dateTime,
        'summary' => $faker->realText(),
        'img_src' => '',
        'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
        'updated_at' => Carbon::now()->format('Y-m-d H:i:s')
    ];
});
