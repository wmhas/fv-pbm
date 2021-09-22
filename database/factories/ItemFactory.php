<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Models\Item;
use Faker\Generator as Faker;
use Illuminate\Support\Str;

$factory->define(Item::class, function (Faker $faker) {
    $x = 1000;
    $number = $x + 1;
    return [
        'item_code'=> $number,
        'item_name'=> $faker->randomElement(['Insulin', 'Actonel' , 'Disprin', 'Paracetamol']),
        'item_expiry_date'=> $faker->dateTimeThisYear('+5 month'),
        'purchase_price'=> $faker->numberBetween($min = 50, $max = 500),
        'purchase_uom'=> $faker->randomElement(['Box', 'Carton']),
        'purchase_quantity'=> $faker->randomDigit,
        'stock_level'=> $faker->randomDigit,
        'selling_price'=> $faker->numberBetween($min = 10, $max = 100),
        'selling_uom'=> $faker->randomElement(['30 Tab', '1 Bottle', '5 Bottle', '60 Tab']),
        'reorder_quantity'=> $faker->randomDigit,
        'service_id'=> $faker->numberBetween($min = 1, $max = 3),
    ];
});
