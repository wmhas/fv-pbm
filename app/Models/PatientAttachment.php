<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PatientAttachment extends Model
{
    protected $fillable = [
         'patient_id','ic_original_filename', 'ic_document_path', 'sl_original_filename','sl_document_path'
    ];
}
