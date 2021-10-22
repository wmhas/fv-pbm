<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Location extends Model
{
    protected $table = 'locations';

    protected $fillable = [
        'item_id',
        'store',
        'counter',
        'staff',
        'courier',
        'on_hand'
    ];

    public function item ()
    {
        return $this->belongsTo(Item::class, 'item_id', 'id');
    }
}
