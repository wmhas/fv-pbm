<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Label extends Model
{
    function order()
    {
        return $this->belongsTo(Order::class, 'order_id');
    }
}
