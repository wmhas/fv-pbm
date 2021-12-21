<?php

if (!function_exists('calculateQuantityPhp')){
	function calculateQuantityPhp($formula_id, $dose_quantity, $frequency, $duration, $formula_value){
	   
        if ($frequency == 1 || $frequency == 5 || $frequency == 6 || $frequency == 7 ||
            $frequency == 8) {
            $frequency = 1;
        } else if ($frequency == 2) {
            $frequency = 2;
        } else if ($frequency == 3) {
            $frequecy = 3;
        } else {
            $frequency = 4;
        }

        if ($formula_id == 1) {
            $quantity = $dose_quantity * $frequency * $duration;
        } else if ($formula_id == 6) {
            $quantity = 1;
        } else {
            $quantity = ($dose_quantity * $frequency * $duration) / $formula_value;
        }
	    
	    return $quantity;
	}
}