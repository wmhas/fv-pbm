<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Route;

class LogController extends Controller
{
    public static function writeInventoryLog($data) {

        $message = "";
        $message .= " Process: " . $data->process . " ||";

        $message .= " Item ID: " . $data->item_id . ",";
        $message .= " Item Name: " . $data->item_name . " ||";

        if ($data->stock_before != null || $data->stock_after != null || $data->stock_changes != null) {
            $message .= " Stock Before: " . $data->stock_before . ";";
            $message .= " Stock After: " . $data->stock_after . ";";
            $message .= " Stock Changes: " . $data->stock_changes . ";";
        }
        
        if ($data->store_before != null || $data->store_after != null || $data->store_changes != null) {
            $message .= " Store Before: " . $data->store_before . ";";
            $message .= " Store After: " . $data->store_after . ";";
            $message .= " Store Changes: " . $data->store_changes . ";";
        }
        
        if ($data->counter_before != null || $data->counter_after != null || $data->counter_changes != null) {
            $message .= " Counter Before: " . $data->counter_before . ";";
            $message .= " Counter After: " . $data->counter_after . ";";
            $message .= " Counter Changes: " . $data->counter_changes . ";";
        }
        
        if ($data->courier_before != null || $data->courier_after != null || $data->courier_changes != null) {
            $message .= " Courier Before: " . $data->courier_before . ";";
            $message .= " Courier After: " . $data->courier_after . ";";
            $message .= " Courier Changes: " . $data->courier_changes . ";";
        }
        
        if ($data->loan_before != null || $data->loan_after != null || $data->loan_changes != null) {
            $message .= " Loan Before: " . $data->loan_before . ";";
            $message .= " Loan After: " . $data->loan_after . ";";
            $message .= " Loan Changes: " . $data->loan_changes . ";";
        }

        $f = fopen (storage_path('logs/inventory.log'), "a");    
    
        $action = Route::getCurrentRoute()->getActionName();
        $text = date("F j, Y, g:i a")." || " . $action . " || " . auth()->user()->name . " || " . $message . "\n";

        fwrite($f, $text);
        fclose($f); 
    }
}
