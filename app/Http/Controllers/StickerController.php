<?php

namespace App\Http\Controllers;

use App\Models\Label;
use App\Models\Order;
use App\Models\Sticker;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;

class StickerController extends Controller
{
    function __construct()
    {
        $this->middleware('permission:sticker', ['only' => ['index', 'delete', 'print']]);
    }

    public function index (Request $request)
    {
        $orders = Order::query();
        $labels = null;

        $doNumber = $request->get('do_number');
        if ($doNumber !== null) {
            $orders = $orders
                ->where('do_number', 'LIKE', '%'.$doNumber.'%')
                ->where('status_id', '!=', 1)
                ->orderBy('updated_at', 'desc')
                ->with(['patient', 'orderitem.items'])
                ->paginate(15);
        } else {
            $labels = Label::groupBy('order_id')->get();
        }

        $roles = DB::table('model_has_roles')->join('users', 'model_has_roles.model_id', '=', 'users.id')->where("users.id", auth()->id())->first();
        return view('sticker.index2', compact('doNumber', 'roles', 'orders', 'labels'));
    }

    public function print ($orderId)
    {
        $roles = DB::table('model_has_roles')->join('users', 'model_has_roles.model_id', '=', 'users.id')->where("users.id", auth()->id())->first();
        $order = Order::where('id', $orderId)->with(['patient', 'orderitem.items', 'orderitem.frequencies'])->first();
        $order_id = $orderId;
        
        if ($order) {
            $data = new \stdClass();
            $data->patient_name = $order->patient->full_name;
            $data->salutation = $order->patient->salutation;
            $data->identification = str_replace('-', '', substr($order->patient->identification, 6, 12));
            $data->do_date = (new Carbon($order->updated_at))->translatedFormat('Y-m-d');
            $data->items = [];

            foreach ($order->orderitem AS $orderItem) {
                $instruction = '';
                if (($orderItem->items->selling_uom === 'TAB' || $orderItem->items->selling_uom === 'CAP') && $orderItem->items->instruction !== 'INHALE/SEDUT') {
                    $instruction = 'AMBIL '.$orderItem->dose_quantity.' BIJI '.$orderItem->frequencies->value.' KALI SEHARI '.$orderItem->items->instruction;
                } else if ($orderItem->items->instruction === 'INHALE/SEDUT') {
                    $instruction = $orderItem->dose_quantity.' SEDUT '.$orderItem->frequencies->value.' KALI SEHARI';
                }

                $sellingUom = $orderItem->items->selling_uom;
                if ($sellingUom === 'TAB') {
                    $sellingUom = 'BIJI';
                }

                $item = new \stdClass();
                $item->name = $orderItem->items->brand_name.' ('.$orderItem->items->generic_name.')';
                $item->instruction = $instruction;
                $item->indication = $orderItem->items->indikasi;
                $item->quantity_uom_duration = $orderItem->quantity.' '.$sellingUom.' ('.$orderItem->duration.' HARI)';
                $data->items[] = $item;
            }

            return view('sticker.print', compact('data', 'roles','order_id'));
        }

        return back()->with(['status' => false, 'message' => 'Please enter correct DO number']);
    }

    public function download (Request $request)
    {
        $items = $request->get('items');
        if ($items) {
            $data = [];
            foreach ($items AS $item) {
                $item['created_at'] = now();
                $item['updated_at'] = now();
                $data[] = $item;
            }
            $inserted = Label::insert($data);
            if ($inserted) {
                return response()->json([
                    'status' => true,
                    'meessage' => 'Data has been printed'
                ]);
            }
        }

        return response()->json([
            'status' => false,
            'message' => 'Failed to print data'
        ], 403);
    }

    public function indexOld(Request $request, $order_id = null)
    {
        $stickers = [];
        $orderPrint = Order::where('id', $order_id)->first();
        $do_number = $request['do_number'];
        if ($do_number != NULL) {
            $order = Order::where('do_number', $request['do_number'])->first();
            if(!empty($order)){
                foreach ($order->orderitem as $order_item) {
                    // $test = substr(' ' . $order_item->items->generic_name . ' ' . $order_item->items->brand_name . '', 0, 15);
                    // dd($order_item->items->detail->frequency);
                    $icno = str_replace('-', '', substr($order_item->order->patient->identification, 6, 12));
                    $sticker = new Sticker();
                    $sticker->salutations = strtoupper($order_item->order->patient->salutation);
                    $sticker->patient_name = strtoupper($order_item->order->patient->full_name);
                    $sticker->item_name = substr(' ' . $order_item->items->brand_name . ' ' , 0, 80);
                    $sticker->quantity = substr($order_item->quantity, 0, 37);
                    $sticker->ic_no = $icno;
                    $sticker->dispensing_date = Carbon::now()->format('Y-m-d');
                    $sticker->instruction = $order_item->items->instruction;
                    $sticker->dose_quantity = $order_item->dose_quantity;
                    $sticker->frequency =  $order_item->items->frequency->name;  //$order_item->items->detail->frequency->value;//
                    $sticker->dose_uom = $order_item->items->selling_uom;
                    $sticker->indikasi = $order_item->items->indikasi;
                    $sticker->p1 = 'SUNTIK';
                    $sticker->p2 = $order_item->dose_quantity;
                    $sticker->p3 = $order_item->items->selling_uom;
                    $sticker->p4 = '(PAGI)';
                    $sticker->p5 = $order_item->dose_quantity;
                    $sticker->p6 = '(T/HARI)';
                    $sticker->p7 = $order_item->dose_quantity;
                    $sticker->p8 = '(UNIT)';
                    $sticker->p9 = '(MLM)';
                    $sticker->p10 = $order_item->dose_quantity;
                    $sticker->p11 = 'MINIT';
                    $sticker->p12 = $order_item->items->instruction;
                    $sticker->p13 = 'AMBIL';
                    $sticker->p14 = 'KALI SEHARI';
                    $sticker->p15 = 'SEDUT';
                    $sticker->save();
                    
                   
                } 
            }else{
                return back()->with(['status' => false, 'message' => 'Please enter correct DO number']);
            }
            
            $stickers = Sticker::all();
            
        }
        $roles = DB::table('model_has_roles')->join('users', 'model_has_roles.model_id', '=', 'users.id')->where("users.id", auth()->id())->first();
        return view('sticker.index', compact('stickers', 'orderPrint', 'roles'))->with(['do_number' => $request['do_number']]);
    }


    public function delete()
    {
        $delete = Sticker::truncate();
        return redirect()->route('sticker.index');
    }

    public function clearQueue (Request $request)
    {
        
        if ((int) $request->doClearLabel == 1) {

            $deleted = Label::truncate();
            if ($deleted) {
                return response()->json([
                    'status' => true,
                    'meessage' => 'Data has been cleared'
                ], 200);
            }

        }

        return response()->json([
            'status' => false,
            'message' => 'Failed to clear queue'
        ], 400);
    }

}
