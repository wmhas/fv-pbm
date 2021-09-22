<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Patient extends Model
{
    protected $fillable = [
        'full_name', 'salutation', 'identification', 'date_of_birth' , 'email' , 'phone','gender','address_1' , 'address_2', 'address_3' , 'postcode' , 'city',
        'state_id', 'confirmation', 'card_id' , 'tariff_id' , 'relation'
    ];

    public function state()
    {
        return $this->belongsTo(State::class , 'state_id');
    }

    public function card()
    {
        return $this->belongsTo(Card::class , 'card_id');
    }

    public function tariff()
    {
        return $this->belongsTo(Tariff::class , 'tariff_id');
    }
}
