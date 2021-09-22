<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Models\Order;
use Faker\Generator as Faker;

$factory->define(Order::class, function (Faker $faker) {
    return [
        'patient_id'=> $faker->numerify('##########'),
        'item_id'=> $faker->unique()->numerify($string = '100##'),
        'dose_quantity'=> $faker->numberBetween($min = 0, $max = 100), 
        'dose_uom'=> $faker->randomElement(['30 Tab', '1 Bottle', '5 Bottle', '60 Tab']),
        'frequency'=> $faker->randomElement(['W', 'D','OD']), 
        'duration'=> $faker->randomElement(['1M', '2M','3M']),  
        'quantity'=> $faker->numberBetween($min = 0, $max = 100),
        'total_amount'=> $faker->numberBetween($min = 0, $max = 100), 
    ];
});