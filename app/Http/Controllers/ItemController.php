<?php

namespace App\Http\Controllers;
use App\Models\Item;
use App\Models\Location;
use App\Models\Frequency;
use App\Models\Formula;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;

class ItemController extends Controller
{
    function __construct()
    {
        $this->middleware('permission:maintenance-item', ['only' => ['index','search']]);
    }

    public function index()
    {
        $items = Item::paginate(15);
        $tarif = NULL;
        $purchase_prices = [];
        foreach ($items as $item){
            $price = $item->purchase_price;
            $quantity = $item->purchase_quantity;
            $purchase_price = $price/$quantity;
            array_push($purchase_prices, [
                'purchase_price' => $purchase_price,
                'item_id' => $item->id,
            ]);
        }
        $roles= DB::table('model_has_roles')->join('users','model_has_roles.model_id', '=', 'users.id')->where("users.id", auth()->id())->first();
        return view('items.index' , compact('items' , 'tarif', 'roles', 'purchase_prices')); 
    }

    public function search(Request $request)
    {
        $keyword = $request->keyword;
        $keyword = preg_replace("/[^a-zA-Z0-9 ]/", "", $keyword);
        $tarif = $request->tarif;
        $stock = $request->get('stock');

        $items = Item::query();

        if ($stock === 'low') {
            $items = $items->whereDoesntHave('stocks', function ($stocks) {
                $stocks->havingRaw('SUM(Quantity) > items.stock_level');
            })->whereNotNull('stock_level');
        }

        if ($keyword) {
            $items = $items->where('item_code', 'like', '%'. strtoupper($keyword). '%')
                ->orwhere('brand_name', 'like', '%'. strtoupper($keyword). '%')
                ->orderBy('item_code', 'asc')
                ->limit(500);
        }

        $items = $items->paginate(15);

        $purchase_prices = [];
        foreach ($items as $item){
            $price = $item->purchase_price;
            $quantity = $item->purchase_quantity;
            $purchase_price = $price/$quantity;
            array_push($purchase_prices, [
                'purchase_price' => $purchase_price,
                'item_id' => $item->id,
            ]);
        }
        $roles= DB::table('model_has_roles')->join('users','model_has_roles.model_id', '=', 'users.id')->where("users.id", auth()->id())->first();
        return view('items.index', compact('keyword', 'items' , 'tarif', 'roles', 'purchase_prices', 'stock'));
    }

    public function view($id)
    {
        $item = Item::where('id', $id)->first();
        $roles = DB::table('model_has_roles')->join('users', 'model_has_roles.model_id', '=', 'users.id')->where("users.id", auth()->id())->first();
        return view('items.view', ['item' => $item, 'roles' => $roles]);
    }

    public function create($id = null)
    {
        $frequencies = Frequency::all();
        $formulas = Formula::all();
        $roles = DB::table('model_has_roles')->join('users', 'model_has_roles.model_id', '=', 'users.id')->where("users.id", auth()->id())->first();
        

        return view('items.create', compact('roles', 'frequencies', 'formulas'));
    }

    public function store_create(Request $request)
    {
        $items = Item::paginate(15);
        $roles = DB::table('model_has_roles')->join('users', 'model_has_roles.model_id', '=', 'users.id')->where("users.id", auth()->id())->first();

        if (!empty($request)){
            $item = Item::create([
                'item_code' => $request->item_code,
                'stock_level' => $request->stock_level,
                'brand_name' => $request->brand_name,
                'generic_name' => $request->generic_name,
                'indication' => $request->indication,
                'indikasi' => $request->indikasi,
                'instruction' => $request->instruction,
                'frequency_id' => $request->frequency,
                'formula_id' => $request->formula,
                'selling_price' => $request->selling_price,
                'selling_uom' => $request->selling_uom,
                'purchase_price' => $request->purchase_price,
                'purchase_uom' => $request->purchase_uom,
                'purchase_quantity' => $request->purchase_quantity,
            ]);

            Location::create([
                'item_id' => $item->id,
                'store' => 0,
                'counter' => 0,
                'staff' => 0,
                'courier' => 0,
                'on_hand' => 0
            ]);


            return redirect()->action('ItemController@view', ['item' => $item, 'roles' => $roles])->with(['status' => true, 'message' => 'Successfully added this item!']);
        }
        return view('items.index' , [ 
            'items' => $items ,
            'roles' => $roles ,
            'tarif' => $tarif = null,
        ]); 
    }

    public function edit($id)
    {
        $frequencies = Frequency::all();
        $formulas = Formula::all();
        $roles = DB::table('model_has_roles')->join('users', 'model_has_roles.model_id', '=', 'users.id')->where("users.id", auth()->id())->first();
        if (!empty($id)) {
            $item = Item::where('id', $id)->first();
            return view('items.edit', compact('item', 'roles', 'frequencies', 'formulas'));
        }
        return view('items.view', compact('roles', 'frequencies', 'formulas'))->with(['status' => false, 'message' => 'This item does not exist!']);
    }

    public function store_edit(Request $request, $id)
    {
        $item = Item::where('id', $id)->first();
        $roles = DB::table('model_has_roles')->join('users', 'model_has_roles.model_id', '=', 'users.id')->where("users.id", auth()->id())->first();

        if (!empty($request)){
            $item->stock_level = $request->input('stock_level');
            $item->brand_name = $request->input('brand_name');
            $item->generic_name = $request->input('generic_name');
            $item->indication = $request->input('indication'); 
            $item->indikasi = $request->input('indikasi');
            $item->instruction = $request->input('instruction');
            $item->frequency_id = $request->input('frequency');
            $item->formula_id = $request->input('formula');
            $item->selling_price = $request->input('selling_price');
            $item->selling_uom = $request->input('selling_uom');
            $item->purchase_price = $request->input('purchase_price');
            $item->purchase_uom = $request->input('purchase_uom');
            $item->purchase_quantity = $request->input('purchase_quantity');
            $item->save();

            return redirect()->action('ItemController@view', ['item' => $item, 'roles' => $roles])->with(['status' => true, 'message' => 'Successfully update this item!']);
        }
        return view('items.view', ['item' => $item, 'roles' => $roles]);
    }
}
