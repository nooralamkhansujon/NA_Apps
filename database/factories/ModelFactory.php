<?php

/*
|--------------------------------------------------------------------------
| Model Factories
|--------------------------------------------------------------------------
|
| Here you may define all of your model factories. Model factories give
| you a convenient way to create models for testing and seeding your
| database. Just tell the factory how a default model should look.
|
*/

/** @var \Illuminate\Database\Eloquent\Factory $factory */
$factory->define(App\User::class, function (Faker\Generator $faker) {
    static $password;

    return [
        'name' => $faker->unique()->name,
        'email' => $faker->unique()->safeEmail,
        'password' => $password ?: $password = bcrypt('testing321'),
        'remember_token' => str_random(10),
    ];
});
 
$factory->define(App\Post::class, function (Faker\Generator $faker) {
    $title = $faker->name;

    return [
        'title' => $title,
        'slug' => str_slug($title),
        'user_id' => 1,
        'post_content' => $faker->text($maxNbChars = 200)
    ];
});