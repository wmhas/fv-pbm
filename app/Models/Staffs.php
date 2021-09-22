<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Staffs extends Model
{
    protected $fillable = [
        'name', 'eg', 'payscale', 'grade', 'tarikh_kemasukan', 'sesi','award'
    ];
}
