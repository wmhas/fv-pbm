<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Card extends Model

{
    protected $fillable = [
        'patient_id','salutation', 'army_pension', 'ic_no', 'name', 'type', 'army_type', 'remark'
    ];

    public function patient()
    {
        return $this->belongsTo(Patient::class ,'patient_id' ,'id');
    }
}
