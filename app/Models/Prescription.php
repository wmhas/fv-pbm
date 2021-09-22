<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Prescription extends Model
{
    protected $fillable = [
        'order_id', 'hospital_id', 'clinic_id', 'rx_number', 'rx_original_filename', 'rx_document_path', 'rx_start', 'rx_end', 'next_supply_date'
    ];

    public function hospital()
    {
        return $this->belongsTo(Hospital::class , 'hospital_id');
    }
    public function clinic()
    {
        return $this->belongsTo(Clinic::class , 'clinic_id');
    }

    public function order()
    {
        return $this->belongsTo(Order::class, 'order_id');
    }

}
