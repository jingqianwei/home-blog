<?php

use Faker\Generator as Faker;

$factory->define(App\Models\Blog::class, function (Faker $faker) {
    // 使用 Faker 类为我们提供的生成随机伪造数据的方法生成数据
    return [
        'title' => $faker->name,
        'content' => $faker->text,
    ];
});
