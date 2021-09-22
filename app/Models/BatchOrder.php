<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BatchOrder extends Model
{
    protected $fillable = [
        'order_id', 'batch_id' ,'tariff_id', 'batchperson_id'
    ];

    public function batch()
    {
        return $this->belongsTo(Batch::class);
    }

    public function order()
    {
        return $this->belongsto(Order::class);
    }
    
    public function tariff()
    {
        return $this->belongsTo(Tariff::class , 'tariff_id');
    }

    public function batchperson()
    {
        return $this->belongsTo(SalesPerson::class , 'batchperson_id');
    }
}
