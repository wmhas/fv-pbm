<?php

namespace App\Models;

use App\Models\Item;
use App\Models\Frequency;
use Illuminate\Database\Eloquent\Model;

class Detail extends Model
{
    //MYOB
    protected $table = 'myob_product_details';

    public function item(){
        return $this->belongsTo(Item::class, 'ItemID', 'id');
    }
    public function frequency(){
        return $this->belongsTo(Frequency::class, 'frequency_id', 'id');
    }
}
