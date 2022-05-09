<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Support\Facades\DB;

class AjaxController extends Controller
{
    public function getDONumber($dispensing_by)
    {
        $increment = 1;
     
        do {
            $count_order = DB::table('orders')->where('do_number', '!=', '')->whereNull('deleted_at')->count();
            $do_number = str_pad($count_order + $increment, 8, "0", STR_PAD_LEFT);

            $exists = Order::where('do_number', $do_number)->first();

            if ($exists)
                $increment++;

        } while ($exists);

        return response()->json($do_number);
    }

    public function getItemDetails($item_id = 0)
    {
        $empData['data'] = DB::table('items as a')
            ->join('frequencies as b', 'b.id', 'a.frequency_id')
            ->join('formulas as c', 'c.id', 'a.formula_id')
            ->select('a.id', 'a.selling_price as selling_price', 'a.selling_uom as selling_uom', 'a.instruction', 'a.indikasi as indication', 'a.formula_id', 'b.name', 'b.id as freq_id', 'c.value')
            ->where('a.id', $item_id)
            ->get()->toArray();

        return response()->json($empData);
    }
}
