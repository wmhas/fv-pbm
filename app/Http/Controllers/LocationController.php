<?php

namespace App\Http\Controllers;

use App\Models\Location;
use App\Models\Item;
use App\Models\Log\InventoryLog;
use Illuminate\Http\Request;
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

        $locations = Location::query();
        $locations = $locations
            ->whereHas('item', function ($item) use ($item_name) {
                if ($item_name) {
                    $item->where('brand_name', 'like', '%' . $item_name . '%');
                }
            })
            ->with(['item.stocks']);

        if ($locations->count() > 0) {
            $locations = $locations->paginate(15);
            return view('location.index', compact('roles', 'item_name', 'locations'));
        }

        return view('location.index', compact('roles', 'item_name'))->with(['status' => false, 'message' => 'Item not found!']);
    }

    public function edit(Request $request, $item_id, $on_hand)
    {
        $location = Location::where('item_id', $item_id)->first();
        $item = Item::where('id', $item_id)->first();
        $store = $on_hand - $request['counter'] - $request['courier']  - $request['staff'];
        if ($request['counter'] + $request['courier']  + $request['staff'] <= $on_hand) {

            // log inventory
            $log = new InventoryLog();
            $log->process = "Move item";
            $log->item_id = $item->id;
            $log->item_name = $item->brand_name;

            $log->store_before = $location->store;
            $log->counter_before = $location->counter;
            $log->courier_before = $location->courier;
            $log->loan_before = $location->staff;

            $location->store = $store;
            $location->counter = $request['counter'];
            $location->courier = $request['courier'];
            $location->staff = $request['staff'];
            $location->save();

            $location = Location::where('item_id', $item_id)->first();

            // log inventory
            $log->store_after = $location->store;
            $log->counter_after = $location->counter;
            $log->courier_after = $location->courier;
            $log->loan_after = $location->staff;

            $log->store_changes = $log->store_after - $log->store_before;
            $log->counter_changes = $log->counter_after - $log->counter_before;
            $log->courier_changes = $log->courier_after - $log->courier_before;
            $log->loan_changes = $log->loan_after - $log->loan_before;
            LogController::writeInventoryLog($log);

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
