<?php

namespace App\Http\Controllers;

use App\Models\Location;
use App\Models\Item;
use App\Models\Stock;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;
use Illuminate\Translation\MessageSelector;

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
