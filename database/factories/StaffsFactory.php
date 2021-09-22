<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Models\Staffs;
use Faker\Generator as Faker;

$factory->define(Staffs::class, function (Faker $faker) {
    return [
        'name' => $faker->name(),
        'eg' => $faker->randomElement(['NE']),
        'payscale' => $faker->randomElement(['TU']),
        'grade' => $faker->randomElement(['TU04','TU06','TU07','TU08']),
       'tarikh_kemasukan'=> $faker->dateTimeBetween($startDate = '1980-01-01', $endDate = '2001-01-01', $timezone = null),
        'sesi' => $faker->randomElement(['1', '2', '3', '4']),
        'award' => $faker->randomElement(['20','25','30','35','40'])
    ];









});

