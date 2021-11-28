<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class NewBatch extends Model
{
    protected $fillable = ['batch_no', 'batch_person', 'batch_status', 'tariff', 'patient_status', 'submission_date'];

    public function orders() {
        return $this->hasMany(Order::class, 'batch_id');
    }

    public function sales_person() {
        return $this->belongsTo(SalesPerson::class, 'batch_person');
    }

    public function tariff() {
        return $this->belongsTo(Tariff::class, 'tariff');
    }

    
}
