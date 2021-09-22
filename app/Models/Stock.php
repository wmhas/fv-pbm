<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Stock extends Model
{
    protected $table = 'stocks';

    protected $fillable = [
        'item_id',
        'quantity',
        'balance',
        'source',
        'source_id',
        'source_date'
    ];

    public $timestamps = false;
}
