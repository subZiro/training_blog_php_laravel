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
        'name' => $faker->name,
        'email' => $faker->unique()->safeEmail,
        'password' => $password ?: $password = bcrypt('secret'),
        'remember_token' => str_random(10),
    ];
});

$factory->define(App\Post::class, function (Faker\Generator $faker) {
    // генерирование постов в бд
    return [
        'title' => $faker->sentence,
        'content' => $faker->sentence,
        'image' => 'blog-4.jpg',
        'date' => '2020-03-04',
        'views' => $faker->numberBetween(0, 5000),
        'category_id' => 1,
        'user_id' => 1,
        'status' => 1,
        'is_featured' => 0
    ];
});

$factory->define(App\Tag::class, function (Faker\Generator $faker) {
    // генерирование тегов 
    return [
        'title' => $faker->word,
    ];
});

$factory->define(App\Category::class, function (Faker\Generator $faker) {
    // генерирование категорий 
    return [
        'title' => $faker->word,
    ];
});

