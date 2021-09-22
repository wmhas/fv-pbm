<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class OrderItem extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'order_id', 'myob_product_id', 'dose_quantity', 'dose_uom','frequency', 'duration', 'instruction',
        'quantity', 'price'
    ];

    public function order()
    {
        return $this->belongsTo(Order::class , 'order_id', 'id');
    }

    public function items()
    {
       return $this->belongsTo(Item::class, 'myob_product_id', 'id')->with('frequency');
    }
}
