<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Purchase extends Model
{
    protected $fillable = [
        'ItemID', 'po_number', 'purchase_price','purchase_uom','quantity' , 'salesperson'
    ];

    public function item()
    {
        return $this->belongsTo(Item::class, 'ItemID', 'id');
    }

    public function salespersons()
    {
        return $this->belongsTo(SalesPerson::class, 'salesperson', 'id');
    }

    
}
