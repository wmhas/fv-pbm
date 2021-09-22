<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Models\Patient;
use Faker\Generator as Faker;

$factory->define(Patient::class, function (Faker $faker) {
    $x = 1;
    $number = $x + 1;
    return [
        'full_name' => $faker->name(),
        'identification' => $faker->numerify('############'),
        'date_of_birth' => $faker->date(),
        'email' => $faker->unique()->safeEmail(),
        'phone' => $faker->phoneNumber(),
        'gender' => $faker->randomElement(['Male', 'Female']),  
        'address_1' => $faker->buildingNumber(),
        'address_2' => $faker->streetName(),
        'address_3' => $faker->streetAddress(),
        'postcode' => $faker->numerify('#####'),
        'city' => $faker->city(),
        'state_id' => $faker->numberBetween($min = 1, $max = 16),
        'confirmation' => $x,       
        'relation' => $faker->randomElement(['Card Owner']),  
     ];
});
