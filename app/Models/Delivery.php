<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Delivery extends Model
{
    protected $fillable = [
        'order_id', 'states_id', 'address_1', 'address_1', 'postcode', 'city', 'method', 'send_date', 'tracking_number', 'file_name', 'document_path'
    ];

    public function state()
    {
        return $this->belongsTo(State::class , 'states_id', 'id');
    }

    public function order()
    {
        return $this->belongsTo(Order::class , 'order_id', 'id');
    }
}
