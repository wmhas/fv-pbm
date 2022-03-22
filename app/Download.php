<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Download extends Model
{
    public function user() {
        return $this->belongsTo(User::class);
    }
}
