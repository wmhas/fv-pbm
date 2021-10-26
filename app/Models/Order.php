<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Order extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'patient_id', 'status_id','total_amount', 'do_number', 'dispensing_by', 'dispense_date', 'dispensing_method',
        'rx_interval', 'order_original_filename', 'order_document_path', 'salesperson_id'
    ];


    function patient()
    {
        return $this->belongsTo(Patient::class, 'patient_id', 'id');
    }

    public function status()
    {
        return $this->belongsTo(Status::class , 'status_id', 'id');
    }

    public function orderitem()
    {
        return $this->hasMany(OrderItem::class , 'order_id', 'id')->with('items');
    }

    public function prescription()
    {
        return $this->hasOne(Prescription::class , 'order_id');
    }

    public function delivery()
    {
        return $this->hasOne(Delivery::class, 'order_id', 'id');
    }

    public function salesperson()
    {
        return $this->belongsTo(SalesPerson::class , 'salesperson_id', 'id');
    }

}
