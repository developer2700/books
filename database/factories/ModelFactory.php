<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Models\User;
use Faker\Generator as Faker;
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

$factory->define(\App\Models\User::class, function (Faker $faker) {
    return [
        'email' => $faker->unique()->safeEmail,
        'email_verified_at' => now(),
        'password' => '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', // password
        'remember_token' => Str::random(10),
    ];
});

$factory->define(\App\Models\Author::class, function (\Faker\Generator $faker) {
    return [
        'first_name' => $faker->unique()->firstName,
        'last_name' => $faker->unique()->lastName,
        'created_at' => \Carbon\Carbon::now(),
    ];
});

$factory->define(\App\Models\Book::class, function (\Faker\Generator $faker) {

    return [
        'title' => $faker->sentence,
        'description' => $faker->text,
        'author_id' => factory(\App\Models\Author::class)->create()->id,
        'status' => 'Published',
        'created_at' => \Carbon\Carbon::now(),
    ];
});


$factory->state(App\Models\Attachment::class, 'with-book', function (\Faker\Generator $faker) {

    return [
        'filename' => $faker->name,
        'created_at' => \Carbon\Carbon::now(),
        'book_id' => factory(\App\Models\Book::class)->create()->id,
    ];
});

$factory->define(App\Models\Attachment::class, function (\Faker\Generator $faker) {

    return [
        'filename' => $faker->name,
        'created_at' => \Carbon\Carbon::now(),
        'book_id' => factory(\App\Models\Book::class)->create()->id,
    ];
});
