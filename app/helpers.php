<?php

if (!function_exists('calculateQuantityPhp')){
	function calculateQuantityPhp($formula_id, $dose_quantity, $frequency, $duration, $formula_value){
	   
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