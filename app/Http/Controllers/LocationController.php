<?php

namespace App\Http\Controllers;

use App\Models\Location;
use App\Models\Item;
use App\Models\Stock;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;

class LocationController extends Controller
{
    function __construct()
    {
        $this->middleware('permission:location', ['only' => ['index', 'edit']]);
    }
    public function index(Request $request)
    {
        $roles = DB::table('model_has_roles')->join('users', 'model_has_roles.model_id', '=', 'users.id')->where("users.id", auth()->id())->first();
        $item_name = $request->get('item_name'); // get request item
        $list_items = [];
        // dd($item_name);
        if ($item_name != null) {
            $items = Item::where('brand_name', 'like', '%' . $item_name . '%')->get();
            foreach ($items as $item) {
                $locations = Location::where('item_id', $item->id)->get();
                $stocks = Stock::selectRaw("SUM(quantity) as quantity, item_id")->where('item_id', $item->id)->groupBy('item_id')->first();
                if ($stocks == null) {
                    return view('location.index', compact('roles', 'item_name', 'list_items'))->with(['status' => false, 'message' => 'Item not found!']);
                }
                // foreach ($stocks as $stock) {
                foreach ($locations as $location) {
                    array_push($list_items, [
                        'item_id' => $item->id,
                        'item_name' => $item->brand_name,
                        'on_hand' => $stocks->quantity,
                        'store' => $location->store,
                        'counter' => $location->counter,
                        'courier' => $location->courier,
                        'staff' => $location->staff,
                    ]);
                }
                // dd($list_items);
                // }
            }
            return view('location.index', compact('roles', 'item_name', 'list_items'));
        }
        // dd('hye');
        return view('location.index', compact('roles', 'item_name', 'list_items'))->with(['status' => false, 'message' => 'Item not found!']);
    }

    public function edit(Request $request, $item_id, $on_hand)
    {
        $location = Location::where('item_id', $item_id)->first();
        $store = $on_hand - $request['counter'] - $request['courier']  - $request['staff'];
        if ($request['counter'] + $request['courier']  + $request['staff'] <= $on_hand) {
            $location->store = $store;
            $location->counter = $request['counter'];
            $location->courier = $request['courier'];
            $location->staff = $request['staff'];
            $location->save();
            return back()->with(['status' => true, 'message' => 'Move item successfully']);
        }
        return back()->with(['status' => false, 'message' => 'Please make sure the quantity is matched!']);
    }

    // public function add_location()
    // {
    //     $items = Item::all();
    //     foreach ($items as $item) {
    //         $location = new Location();
    //         $location->item_id = $item->ItemID;
    //         $location->save();
    //     }
    // }
}
