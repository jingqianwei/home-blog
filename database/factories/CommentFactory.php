<?php

use Faker\Generator as Faker;

$factory->define(App\Models\Comment::class, function (Faker $faker) {
    return [
        'content' => $faker->text,
        'blog_id' => rand(1,50),
        'user_id' => rand(1,50),
    ];
});
