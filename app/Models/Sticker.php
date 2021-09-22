<?php

namespace App\Models;

use App\Models\Order;
use Illuminate\Database\Eloquent\Model;

class Sticker extends Model
{
    protected $table = 'stickers';

    protected $fillable = [
        'patient_name',
        'item_name',
        'quantity',
        'ic_no',
        'dispensing_date',
        'instruction',
        'dose_quantity',
        'frequency',
        'dose_uom',
        'indikasi',
        'salutations'

    ];

    public function order()
    {
        return $this->belongsTo(Order::class, 'order_id');
    }
}
