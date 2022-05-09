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
            $labels = Label::where('user_id', '=', auth()->user()->id)->groupBy('order_id')->get();
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
            $data->do_date = (new Carbon())->translatedFormat('Y-m-d');
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
    
    public function delete()
    {
        $delete = Sticker::truncate();
        return redirect()->route('sticker.index');
    }

    public function clearQueue (Request $request)
    {
        
        if ((int) $request->doClearLabel == 1) {

            $deleted = Label::where('user_id', auth()->user()->id)->delete();
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
