<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    protected function getMimeType($filename)
    {
        $ext = pathinfo($filename, PATHINFO_EXTENSION);
        switch (strtolower($ext)) {
            case('css'):
                return 'text/css';
            case('csv'):
                return 'text/csv';
            case('doc'):
            case('docx'):
                return 'application/msword';
            case('gif'):
                return 'image/gif';
            case('jpg'):
            case('jpeg'):
                return 'image/jpeg';
            case('png'):
                return 'image/png';
            case('ppt'):
            case('pptx'):
                return 'application/vnd.ms-powerpoint';
            case('xls'):
            case('xlsx'):
                return 'application/vnd.ms-excel';
            case('zip'):
                return 'application/zip';
            case('pdf'):
            default:
                return 'application/pdf';
        }
    }
}
