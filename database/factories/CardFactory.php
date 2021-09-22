<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Models\Card;
use Faker\Generator as Faker;

$factory->define(Card::class, function (Faker $faker) {
    $x = 1;
    $number = $x + 1;
    return [
        'patient_id' => $x,
        'army_pension' => $faker->bothify('T#####'),
        'card_ic_no' => $faker->numerify('############'),
        'card_name' => $faker->name(),
        'card_type' => $faker->randomElement(['PENSIONABLE VETERAN', 'NON-PENSIONABLE VETERAN']), 
        'army_type' => $faker->randomElement(['ATM', 'Kerahan Sepenuh Masa', 'Force 136', 'Tentera British', 'Sarawak Rangers']),          
     ];
});
