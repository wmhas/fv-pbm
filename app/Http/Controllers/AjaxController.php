<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AjaxController extends Controller
{
    public function getDONumberOld($dispensing_by)
    {
        if($dispensing_by == 'FVKL'){
            $count_order = DB::table('orders')->where('dispensing_by', 'FVKL')->where('do_number', '!=', '')->count();
            $code = '50';
        } elseif ($dispensing_by == 'FVT')  {
            $count_order = DB::table('orders')->where('dispensing_by', 'FVT')->where('do_number', '!=', '')->count();
            $code = '14';
        } else {
            $count_order = DB::table('orders')->where('dispensing_by', 'FVL')->where('do_number', '!=', '')->count();
            $code = '99';
        }
        $number = str_pad($count_order + 1, 6, "0", STR_PAD_LEFT);
        $do_number = $code.$number;
        return response()->json($do_number);
    }

    public function getDONumber($dispensing_by)
    {
        $count_order = DB::table('orders')->where('do_number', '!=', '')->count();
        $do_number = str_pad($count_order + 1, 8, "0", STR_PAD_LEFT);
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
