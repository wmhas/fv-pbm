<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
class Batch extends Model
{
    protected $fillable = ['batch_status' , 'batch_no' , 'tariff_id'];

    public function batchOrder()
    {
        return $this->hasMany(BatchOrder::class);
    }

    public function tariff()
    {
        return $this->belongsTo(Tariff::class , 'tariff_id');
    }
}
