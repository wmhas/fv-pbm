<?php

namespace App\Http\Controllers;

use App\Models\Location;
use App\Models\Order;
use App\Models\Item;
use App\Models\Patient;
use App\Models\OrderItem;
use App\Models\State;
use App\Models\Status;
use App\Models\Delivery;
use App\Models\Prescription;
use App\Models\Hospital;
use App\Models\Clinic;
use App\Models\Frequency;
use App\Models\SalesPerson;
use App\Models\Stock;
use PDF;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Exception;

class OrderController extends Controller
{
    function __construct()
    {
        $this->middleware('permission:order-index', ['only' => ['index', 'search', 'show', 'history']]);
        $this->middleware('permission:order-edit', ['only' => ['edit', 'store_edit']]);
        $this->middleware(
            'permission:order-management',
            [
                'only' => [
                    'create_order', 'store_dispense', 'create_prescription', 'store_prescription', 'create_orderEntry', 'store_orderEntry', 'store_item', 'delete_item', 'deleteOrder', 'dispense_order', 'complete_order', 'return_order', 'return_order_item'
                ]
            ]
        );
        $this->middleware('permission:order-resubmission', ['only' => ['resubmission']]);
    }
    public function index()
    {
        $orders = Order::with('prescription')->with('patient')->with('delivery')->orderBy('status_id', 'asc')
            ->orderBy('created_at', 'desc')
            ->paginate(15);
        $method = null;
        $keyword = null;
        $status_id = null;
        $statuses = Status::all();
        $roles = DB::table('model_has_roles')->join('users', 'model_has_roles.model_id', '=', 'users.id')->where("users.id", auth()->id())->first();
        return view('orders.index', compact('orders', 'method', 'keyword', 'status_id', 'statuses', 'roles'));
    }

    public function search(Request $request)
    {
        $statuses = Status::all();
        $method = $request->get('method');
        $status_id = $request->get('status');
        $keyword = $request->get('keyword');
        $keyword = preg_replace("/[^a-zA-Z0-9 ]/", "", $keyword);
        if ($method != null && $status_id != null && $keyword != null) {
            $orders = Order::with('prescription')->with('patient')->with('delivery')->where('dispensing_method', 'like', '%' . strtoupper($method) . '%')
                ->where('status_id', 'like', '%' . strtoupper($status_id) . '%')
                ->where('do_number', 'like', '%' . strtoupper($keyword) . '%')
                ->orderBy('status_id', 'asc')
                ->orderBy('created_at', 'desc')->limit(500)
                ->paginate(15);
        } elseif ($method != null && $status_id != null && $keyword == null) {
            $orders = Order::with('prescription')->with('patient')->with('delivery')->where('dispensing_method', 'like', '%' . strtoupper($method) . '%')
                ->where('status_id', 'like', '%' . strtoupper($status_id) . '%')
                ->orderBy('status_id', 'asc')
                ->orderBy('created_at', 'desc')->limit(500)
                ->paginate(15);
        } elseif ($method != null && $status_id == null && $keyword != null) {
            $orders = Order::with('prescription')->with('patient')->with('delivery')->where('dispensing_method', 'like', '%' . strtoupper($method) . '%')
                ->where('do_number', 'like', '%' . strtoupper($keyword) . '%')
                ->orderBy('status_id', 'asc')
                ->orderBy('created_at', 'desc')->limit(500)
                ->paginate(15);
        } elseif ($method == null && $status_id != null && $keyword != null) {
            $orders = Order::with('prescription')->with('patient')->with('delivery')->where('status_id', 'like', '%' . strtoupper($status_id) . '%')
                ->where('do_number', 'like', '%' . strtoupper($keyword) . '%')
                ->orderBy('status_id', 'asc')
                ->orderBy('created_at', 'desc')->limit(500)
                ->paginate(15);
        } elseif ($method != null && $status_id == null && $keyword == null) {
            $orders = Order::with('prescription')->with('patient')->with('delivery')->where('dispensing_method', 'like', '%' . strtoupper($method) . '%')
                ->orderBy('status_id', 'asc')
                ->orderBy('created_at', 'desc')->limit(500)
                ->paginate(15);
        } elseif ($method == null && $status_id != null && $keyword == null) {
            $orders = Order::with('prescription')->with('patient')->with('delivery')->where('status_id', 'like', '%' . strtoupper($status_id) . '%')
                ->orderBy('status_id', 'asc')
                ->orderBy('created_at', 'desc')->limit(500)
                ->paginate(15);
        } elseif ($method == null && $status_id == null && $keyword != null) {
            $orders = Order::with('prescription')->with('patient')->with('delivery')->where('do_number', 'like', '%' . strtoupper($keyword) . '%')
                ->orderBy('status_id', 'asc')
                ->orderBy('created_at', 'desc')->limit(500)
                ->paginate(15);
        } else {
            $orders = Order::with('prescription')->with('patient')->with('delivery')->orderBy('status_id', 'asc')
                ->orderBy('created_at', 'desc')
                ->paginate(15);
        }
        $roles = DB::table('model_has_roles')->join('users', 'model_has_roles.model_id', '=', 'users.id')->where("users.id", auth()->id())->first();
        return view('orders.index', compact('keyword', 'orders', 'method', 'statuses', 'status_id', 'roles'));
    }

    public function show($id)
    {
        $order = Order::findOrFail($id);
        if ($order->dispensing_method == 0 && $order->rx_interval == 0 && $order->total_amount == 0) {
            return redirect()->action('OrderController@create_order', [
                'order_id' => $order->id,
                'patient' => $order->patient_id
            ]);
        } elseif ($order->dispensing_method != 0 && $order->rx_interval == 0 && $order->total_amount == 0) {
            return redirect()->action('OrderController@create_prescription', [
                'order_id' => $order->id,
                'id' => $order->patient_id
            ]);
        } elseif ($order->dispensing_method != 0 && $order->rx_interval != 0 && $order->total_amount == 0) {
            return redirect()->action('OrderController@create_orderEntry', [
                'order_id' => $order->id,
                'id' => $order->patient_id
            ]);
        } else {
            $salesPersons = SalesPerson::all();
            $roles = DB::table('model_has_roles')->join('users', 'model_has_roles.model_id', '=', 'users.id')->where("users.id", auth()->id())->first();
            return view('orders.view', compact('order', 'roles', 'salesPersons'));
        }
    }

    public function edit($id)
    {
        $states = State::all();
        $hospitals = Hospital::all();
        $clinics = Clinic::all();
        $salesPersons = SalesPerson::all();
        $order = Order::where('id', $id)->first();
        // $items = DB::table('myob_products as a')->join('myob_product_details as b', 'b.myob_product_id', 'a.ItemNumber')->where('IsInactive', 'N')->get();
        $items = Item::all();
        $item_lists = [];
        foreach ($items as $item) {
            $location = DB::table('locations')->where('item_id', $item->id)->first();
            if ($order->dispensing_method == "Walkin") {
                array_push($item_lists, [
                    'id' => $item->id,
                    'brand_name' => $item->brand_name,
                    'code' => $item->item_code,
                    'quantity' => $location->counter != null ? $location->counter : 0,
                ]);
            } else {
                array_push($item_lists, [
                    'id' => $item->id,
                    'brand_name' => $item->brand_name,
                    'code' => $item->item_code,
                    'quantity' => $location->courier != null ? $location->courier : 0,
                ]);
            }
        }
        $frequencies = Frequency::all();
        $resubmission = 0;
        $roles = DB::table('model_has_roles')->join('users', 'model_has_roles.model_id', '=', 'users.id')->where("users.id", auth()->id())->first();
        return view('orders.edit', compact('states', 'hospitals', 'clinics', 'salesPersons', 'order', 'items', 'item_lists', 'frequencies', 'roles', 'resubmission'));
    }

    public function store_edit($id, Request $request)
    {
        $order = Order::where('id', $id)->first();
        $order->salesperson_id = $request->input('salesperson');
        $order->do_number = $request->input('do_number');
        $order->dispensing_by = $request->input('dispensing_by');
        $order->dispensing_method = $request->input('dispensing_method');
        $order->rx_interval = $request->input('rx_interval');
        $order->total_amount = $request->input('total_amount');
        $order->save();

        if ($order->dispensing_method == 'Delivery') {
            if (empty($order->delivery)) {
                $delivery = new Delivery();
                $delivery->order_id = $id;
                $delivery->states_id = $request->input('dispensing_state');
                $delivery->save();
            }
            $delivery = Delivery::where('order_id', $id)->first();

            $delivery->method = $request->input('delivery_method');
            $delivery->send_date = $request->input('send_date');
            $delivery->tracking_number = $request->input('tracking_number');
            $delivery->address_1 = $request->input('dispensing_add1');
            $delivery->address_2 = $request->input('dispensing_add2');
            $delivery->postcode = $request->input('dispensing_postcode');
            $delivery->city = $request->input('dispensing_city');
            if ($request->hasFile('cn_attach')) {
                $fileNameWithExt = $request->file('cn_attach')->getClientOriginalName();
                $fileName = pathinfo($fileNameWithExt, PATHINFO_FILENAME);
                $extension = $request->file('cn_attach')->getClientOriginalExtension();
                $fileNameToStore = $fileName . '.' . $extension;
                $path = $request->file('cn_attach')->storeAs('public/order/' . $order->id . '/consignment-note/', $fileNameToStore);
                $document_path = 'public/order/' . $order->id . '/consignment-note/' . $fileNameToStore;
                $delivery->file_name = $fileNameToStore;
                $delivery->document_path = $document_path;
            }
            $delivery->save();
        }
        if (empty($order->prescription)) {
            $prescription = new Prescription();
            $prescription->order_id = $id;
            $prescription->hospital_id = $request->input('rx_hospital');
            $prescription->clinic_id = $request->input('rx_clinic');
            $prescription->rx_number = $request->input('rx_number');
            $prescription->rx_start = $request->input('rx_start_date');
            $prescription->rx_end = $request->input('rx_end_date');
            $prescription->save();
        } else {
            $order->prescription->hospital_id = $request->input('rx_hospital');
            $order->prescription->clinic_id = $request->input('rx_clinic');
            $order->prescription->rx_number = $request->input('rx_number');
            $order->prescription->rx_start = $request->input('rx_start_date');
            $order->prescription->rx_end = $request->input('rx_end_date');
            $order->prescription->save();
        }
        $prescription = Prescription::where('order_id', $id)->first();

        if ($request->hasFile('rx_attach')) {
            $fileNameWithExt = $request->file('rx_attach')->getClientOriginalName();
            $fileName = pathinfo($fileNameWithExt, PATHINFO_FILENAME);
            $extension = $request->file('rx_attach')->getClientOriginalExtension();
            $fileNameToStore = $fileName . '.' . $extension;
            $path = $request->file('rx_attach')->storeAs('public/order/' . $order->id . '/rx-attachment/', $fileNameToStore);
            $document_path = 'public/order/' . $order->id . '/rx-attachment/' . $fileNameToStore;
            $prescription->rx_original_filename = $fileNameToStore;
            $prescription->rx_document_path = $document_path;
        }
        if ($order->rx_interval == "2") {
            $prescription->next_supply_date = $request->input('rx_supply_date');
        } elseif ($order->rx_interval == '1') {
            $prescription->next_supply_date = NULL;
        }
        $prescription->save();
        if ($order->total_amount != 0) {
            return redirect()->action('OrderController@show', ['order' => $order->id])
                ->with(['status' => true, 'message' => 'Order Updated!']);
        } else {
            return redirect()->back()
                ->with(['status' => false, 'message' => 'Insufficient item stock or incorrect details!']);
        }
    }

    public function history($patient)
    {
        $patient = Patient::findOrFail($patient);
        $orders = Order::with('delivery')->where('patient_id', $patient->id)->orderBy('created_at', 'desc')->paginate(15);
        $roles = DB::table('model_has_roles')->join('users', 'model_has_roles.model_id', '=', 'users.id')->where("users.id", auth()->id())->first();
        return view('orders.history', compact('patient', 'orders', 'roles'));
    }

    public function create_order($patient, $order = null)
    {
        $states = DB::table('states')->select("id","name")->get();
        $order = Order::where('id', $order)->first();
        $salesPersons = SalesPerson::all();
        if ($order == null) {
            $order = new Order();
            $order->patient_id = $patient;
            $order->total_amount = 0;
            $order->status_id = 1;
            $order->dispensing_method = 0;
            $order->rx_interval = 0;
            $order->save();
        }
        $roles = DB::table('model_has_roles')->join('users', 'model_has_roles.model_id', '=', 'users.id')->where("users.id", auth()->id())->first();
        return view('orders.create.create_order1', compact('order', 'states', 'roles', 'salesPersons'));
    }

    public function store_dispense($patient, $order_id, Request $request)
    {
        $order = Order::where('id', $order_id)->first();
        $order->salesperson_id = $request->input('salesperson');
        $order->do_number = $request->input('do_number');
        $order->dispensing_by = $request->input('dispensing_by');
        $order->dispensing_method = $request['dispensing_method'];
        $order->save();
        if ($order->dispensing_method == 'Delivery') {
            if (empty($order->delivery)) {
                $delivery = new Delivery();
                $delivery->order_id = $order_id;
                $delivery->states_id = $request->input('dispensing_state');
                $delivery->save();
            } else {
                $order->delivery->states_id = $request->input('dispensing_state');
                $order->delivery->save();
            }
            $delivery = Delivery::where('order_id', $order_id)->first();
            $delivery->method = $request->input('delivery_method');
            $delivery->send_date = $request->input('send_date');
            $delivery->tracking_number = $request->input('tracking_number');
            $delivery->address_1 = $request->input('dispensing_add1');
            $delivery->address_2 = $request->input('dispensing_add2');
            $delivery->postcode = $request->input('dispensing_postcode');
            $delivery->city = $request->input('dispensing_city');
            if ($request->hasFile('cn_attach')) {
                $fileNameWithExt = $request->file('cn_attach')->getClientOriginalName();
                $fileName = pathinfo($fileNameWithExt, PATHINFO_FILENAME);
                $extension = $request->file('cn_attach')->getClientOriginalExtension();
                $fileNameToStore = $fileName . '.' . $extension;
                $path = $request->file('cn_attach')->storeAs('public/order/' . $order->id . '/consignment-note/', $fileNameToStore);
                $document_path = 'public/order/' . $order->id . '/consignment-note/' . $fileNameToStore;
                $delivery->file_name = $fileNameToStore;
                $delivery->document_path = $document_path;
            }
            $delivery->save();
        }
        return redirect()->action('OrderController@create_prescription', [
            'id' => $patient,
            'order_id' => $order_id
        ]);
    }

    public function create_prescription($patient, $order_id)
    {
        $hospitals = DB::table('hospitals')->select("id","name")->get();
        $clinics = DB::table('clinics')->select("id","name")->get();
        $order = Order::where('id', $order_id)->first();
        if ($order->rx_interval == 0) {
            $order->rx_interval = 2;
            $order->save();
        }
        $roles = DB::table('model_has_roles')->join('users', 'model_has_roles.model_id', '=', 'users.id')->where("users.id", auth()->id())->first();
        return view('orders.create.create_order2', compact('order', 'hospitals', 'clinics', 'roles'));
    }

    public function store_prescription($patient, $order_id, Request $request)
    {
        $order = Order::where('id', $order_id)->first();
        $order->rx_interval = $request->input('rx_interval');
        $order->save();
        if (empty($order->prescription)) {
            $prescription = new Prescription();
            $prescription->order_id = $order_id;
            $prescription->hospital_id = $request->input('rx_hospital');
            $prescription->clinic_id = $request->input('rx_clinic');
            $prescription->rx_number = $request->input('rx_number');
            $prescription->rx_start = $request->input('rx_start_date');
            $prescription->rx_end = $request->input('rx_end_date');
            $prescription->save();
        } else {
            $order->prescription->hospital_id = $request->input('rx_hospital');
            $order->prescription->clinic_id = $request->input('rx_clinic');
            $order->prescription->rx_number = $request->input('rx_number');
            $order->prescription->rx_start = $request->input('rx_start_date');
            $order->prescription->rx_end = $request->input('rx_end_date');
            $order->prescription->save();
        }
        $prescription = Prescription::where('order_id', $order_id)->first();

        if ($request->hasFile('rx_attach')) {
            $fileNameWithExt = $request->file('rx_attach')->getClientOriginalName();
            $fileName = pathinfo($fileNameWithExt, PATHINFO_FILENAME);
            $extension = $request->file('rx_attach')->getClientOriginalExtension();
            $fileNameToStore = $fileName . '.' . $extension;
            $path = $request->file('rx_attach')->storeAs('public/order/' . $order->id . '/rx-attachment/', $fileNameToStore);
            $document_path = 'public/order/' . $order->id . '/rx-attachment/' . $fileNameToStore;
            $prescription->rx_original_filename = $fileNameToStore;
            $prescription->rx_document_path = $document_path;
        }
        if ($order->rx_interval == '2') {
            $prescription->next_supply_date = $request->input('rx_supply_date');
        } elseif ($order->rx_interval == '1') {
            $prescription->next_supply_date = NULL;
        }
        $prescription->save();

        return redirect()->action('OrderController@create_orderEntry', [
            'id' => $patient,
            'order_id' => $order_id
        ]);
    }

    public function create_orderEntry($patient, $order_id)
    {
        ini_set('max_execution_time', 300);
        // $items = DB::table('myob_products as a')->join('myob_product_details as b', 'b.myob_product_id', 'a.ItemNumber')->where('IsInactive', 'N')->get();
        $items = Item::select('id','brand_name','item_code')->get();
        $order = Order::where('id', $order_id)->first();

        $orderItems = $order->orderitem;

        $item_lists = [];
        foreach ($items as $item) {
            $location = DB::table('locations')->select('counter','courier')->where('item_id', $item->id)->first();

            if ($order->dispensing_method == "Walkin") {
                array_push($item_lists, [
                    'id' => $item->id,
                    'brand_name' => $item->brand_name,
                    'code' => $item->item_code,
                    'quantity' => $location->counter != null ? $location->counter : 0,
                ]);
            } else {
                array_push($item_lists, [
                    'id' => $item->id,
                    'brand_name' => $item->brand_name,
                    'code' => $item->item_code,
                    'quantity' => $location->courier != null ? $location->courier : 0,
                ]);
            }
        }
        $frequencies = Frequency::all();

        $roles = DB::table('model_has_roles')->join('users', 'model_has_roles.model_id', '=', 'users.id')->where("users.id", auth()->id())->first();
        return view('orders.create.create_order3', compact('order', 'orderItems', 'item_lists', 'roles', 'frequencies'));
    }

    public function store_orderEntry($patient, $order_id, Request $request)
    {
        $order = Order::where('id', $order_id)->first();
        
        // dd($request->all());
  
        if ( $request->input('total_amount') == 0) {
            return redirect()->action('OrderController@create_orderEntry', [
                'id' => $patient,
                'order_id' => $order_id
            ])->with(['status' => false, 'message' => 'You need to add at least one item to create an order!']);
        } else {
            $order->total_amount = $request->input('total_amount');
            $order->status_id = 2;
            $order->update();
            return redirect()->action('OrderController@show', [
                'order' => $order_id
            ]);
        }
    }

    //Move to ajax controller getItemDetails
    // public function getDetails($item_id = 0)
    // {
    //     $empData['data'] = DB::table('myob_products as a')->join('myob_product_details as b', 'b.myob_product_id', 'a.ItemNumber')->join('frequencies as c', 'c.id', 'b.frequency_id')
    //         ->join('formulas as d', 'd.id', 'b.formula_id')
    //         ->select('a.ItemID', 'a.BaseSellingPrice as selling_price', 'a.SellUnitMeasure as selling_uom', 'b.instruction', 'b.indikasi as indication', 'b.formula_id', 'c.name', 'c.id as freq_id', 'd.value')
    //         ->where('a.ItemID', $item_id)
    //         ->get()->toArray();

    //     return response()->json($empData);
    // }

    public function store_item(Request $request)
    {
        $order = Order::select("id","dispensing_method","patient_id")->where('id',  $request->input('order_id'))->first();
        $location = Location::where('item_id', $request->input('item_id'))->first();
        if ($order->dispensing_method == 'Walkin' && $location->counter >= $request->input('quantity')) {
            $location->counter = $location->counter - $request->input('quantity');
            $location->save();
        } elseif ($order->dispensing_method == 'Delivery' && $location->courier >= $request->input('quantity')) {
            $location->courier = $location->courier - $request->input('quantity');
            $location->save();
        } else {
            return redirect()->action('OrderController@create_orderEntry', ['patient' => $order->patient_id, 'order_id', $order->id])->with(['status' => false, 'message' => 'Item quantity exceeded the number of quantity available']);
        }

        $record = new OrderItem();
        $record->order_id = $request->input('order_id');
        $record->myob_product_id = $request->input('item_id');
        $record->dose_quantity = $request->input('dose_quantity');
        $record->duration = $request->input('duration');
        $record->frequency = $request->input('frequency');
        $record->quantity = $request->input('quantity');
        $record->price = $request->input('price');
        $record->save();

        $stock = new Stock();
        $stock->item_id = $request->input('item_id');
        $stock->quantity = -$request->input('quantity');
        $stock->balance = 0;
        $stock->source = 'sale';
        $stock->source_id = $record->id;
        $stock->source_date = Carbon::now()->format('Y-m-d');
        $stock->save();

        if ($order->total_amount == 0) {
            return redirect()->route('order.entry', [
                'id' => $request->input('patient_id'),
                'order_id' => $request->input('order_id')
            ])->with(['status' => true, 'message' => 'Successfully add item']);
        } else {
            return redirect()->route('order.update', [
                'order' => $request->input('order_id')
            ])->with(['status' => true, 'message' => 'Successfully add item']);
        }
    }

    public function delete_item($patient, $id)
    {
        $order_item = OrderItem::where('id', $id)->first();
        $order = Order::where('id', $order_item->order_id)->first();
        $location = Location::where('item_id', $order_item->myob_product_id)->first();
        try {
            if ($order->dispensing_method == 'Walkin') {
                $location->counter = $location->counter + $order_item->quantity;
                $location->save();
            } elseif ($order->dispensing_method == 'Delivery') {
                $location->courier = $location->courier + $order_item->quantity;
                $location->save();
            } else {
                return redirect()->action('OrderController@create_orderEntry', ['patient' => $order->patient_id, 'order_id', $order->id])->with(['status' => false, 'message' => 'Item quantity exceeded the number of quantity available']);
            }

            $stock = new Stock();
            $stock->item_id = $order_item->myob_product_id;
            $stock->quantity = $order_item->quantity;
            $stock->balance = 0;
            $stock->source = 'return';
            $stock->source_id = $order_item->id;
            $stock->source_date = Carbon::now()->format('Y-m-d');
            $stock->save();

            $order_item->delete();
            if ($order->total_amount == "0") {
                return redirect()->route('order.entry', [
                    'id' => $patient,
                    'order_id' => $order_item->order_id
                ])->with(['status' => true, 'message' => 'Successfully delete']);
            } else {
                return redirect()->route('order.update', [
                    'order' => $order_item->order_id
                ])->with(['status' => true, 'message' => 'Successfully delete']);
            }
        } catch (Exception $e) {
            if ($order->total_amount == "0") {
                return redirect()->route('order.entry', [
                    'id' => $order->patient_id,
                    'order_id' => $order->id
                ])
                    ->with(['status' => false, 'message' => 'Failed to delete item']);
            } else {
                return redirect()->route('order.update', [
                    'order' => $order->id
                ])
                    ->with(['status' => false, 'message' => 'Failed to delete item']);
            }
        }
    }

    public function deleteOrder($order)
    {
        $order = Order::findorfail($order);
        $order_items = OrderItem::where('order_id', $order->id)->get();
        foreach ($order_items as $oi) {
            $location = Location::where('item_id', $oi->myob_product_id)->first();
            if ($order->dispensing_method == 'Walkin') {
                $location->counter = $location->counter + $oi->quantity;
                $location->save();
            } elseif ($order->dispensing_method == 'Delivery') {
                $location->courier = $location->courier + $oi->quantity;
                $location->save();
            } else {
                echo 'error encountered';
            }
            $location->save();

            $stock = new Stock();
            $stock->item_id = $oi->item_id;
            $stock->quantity = $oi->quantity;
            $stock->balance = 0;
            $stock->source = 'return';
            $stock->source_id = $oi->id;
            $stock->source_date = Carbon::now()->format('Y-m-d');
            $stock->save();
        }
        $order->delete();
        return redirect()->action('OrderController@index')->with(['status' => true, 'message' => 'Successfully delete order']);
    }

    public function downloadConsignmentNote($id)
    {
        $delivery = Delivery::findorfail($id);
        if (!empty($delivery)) {
            if (!empty($delivery->document_path)) {
                $contents = Storage::get($delivery->document_path);
                $ext = pathinfo($delivery->document_path, PATHINFO_EXTENSION);
                $resp = response($contents)->header('Content-Type', $this->getMimeType($delivery->document_path));
                $resp->header('Content-Disposition', 'inline; filename="' . $delivery->file_name . '.' . $ext .   '"');
                return $resp;
            }
        }
        return null;
    }

    public function updateConsignmentNote(Request $request, $id)
    {
        $delivery = Delivery::findorfail($id);
        $order = Order::where('id', $delivery->order_id)->first();

        if ($request->hasFile('cn_attach')) {
            $fileNameWithExt = $request->file('cn_attach')->getClientOriginalName();
            $fileName = pathinfo($fileNameWithExt, PATHINFO_FILENAME);
            $extension = $request->file('cn_attach')->getClientOriginalExtension();
            $fileNameToStore = $fileName . '.' . $extension;
            //delete previous file attachment
            unlink(storage_path('app/public/order/' . $order->id . '/consignment-note/' . $delivery->file_name));
            $path = $request->file('cn_attach')->storeAs('public/order/' . $order->id . '/consignment-note/', $fileNameToStore);
            $document_path = 'public/order/' . $order->id . '/consignment-note/' . $fileNameToStore;
            $delivery->file_name = $fileNameToStore;
            $delivery->document_path = $document_path;
            $delivery->save();
        }
        return redirect()->action('OrderController@create_order', [
            'patient' => $order->patient_id,
            'order_id' => $order->id
        ]);
    }

    public function downloadRXAttachment($id)
    {
        $rx_attach = Prescription::findorfail($id);
        if (!empty($rx_attach)) {
            if (!empty($rx_attach->rx_document_path)) {
                $contents = Storage::get($rx_attach->rx_document_path);
                $ext = pathinfo($rx_attach->rx_document_path, PATHINFO_EXTENSION);
                $resp = response($contents)->header('Content-Type', $this->getMimeType($rx_attach->rx_document_path));
                $resp->header('Content-Disposition', 'inline; filename="' . $rx_attach->rx_original_filename . '.' . $ext .   '"');
                return $resp;
            }
        }
        return null;
    }

    public function updateRXAttachment(Request $request, $id)
    {
        $prescription = Prescription::findorfail($id);
        $order = Order::where('id', $prescription->order_id)->first();

        if ($request->hasFile('rx_attach')) {
            $fileNameWithExt = $request->file('rx_attach')->getClientOriginalName();
            $fileName = pathinfo($fileNameWithExt, PATHINFO_FILENAME);
            $extension = $request->file('rx_attach')->getClientOriginalExtension();
            $fileNameToStore = $fileName . '.' . $extension;
            //delete previous file attachment
            unlink(storage_path('app/public/order/' . $order->id . '/rx-attachment/' . $prescription->rx_original_filename));
            $path = $request->file('rx_attach')->storeAs('public/order/' . $order->id . '/rx-attachment/', $fileNameToStore);
            $document_path = 'public/order/' . $order->id . '/rx-attachment/' . $fileNameToStore;
            $prescription->rx_original_filename = $fileNameToStore;
            $prescription->rx_document_path = $document_path;
            $prescription->save();
        }
        return redirect()->action('OrderController@create_prescription', [
            'id' => $order->patient_id,
            'order_id' => $order->id
        ]);
    }

    public function uploadOrderAttachment(Request $request, $id)
    {
        $order = Order::findorfail($id);
        if ($request->hasFile('order_attach')) {
            $fileNameWithExt = $request->file('order_attach')->getClientOriginalName();
            $fileName = pathinfo($fileNameWithExt, PATHINFO_FILENAME);
            $extension = $request->file('order_attach')->getClientOriginalExtension();
            $fileNameToStore = $fileName . '.' . $extension;
            $path = $request->file('order_attach')->storeAs('public/order/' . $order->id . '/order-attachment/', $fileNameToStore);
            $document_path = 'public/order/' . $order->id . '/rx-attachment/' . $fileNameToStore;
            $order->order_original_filename = $fileNameToStore;
            $order->order_document_path = $document_path;
            $order->save();
        }
        return redirect()->action('OrderController@show', ['order' => $order->id])
            ->with(['status' => true, 'message' => 'Order Attachment Uploaded Sucessfully !']);
    }

    public function downloadOrderAttachment($id)
    {
        $order = Order::findorfail($id);
        if (!empty($order)) {
            if (!empty($order->order_document_path)) {
                $contents = Storage::get($order->order_document_path);
                $ext = pathinfo($order->order_document_path, PATHINFO_EXTENSION);
                $resp = response($contents)->header('Content-Type', $this->getMimeType($order->order_document_path));
                $resp->header('Content-Disposition', 'inline; filename="' . $order->order_original_filename . '.' . $ext .   '"');
                return $resp;
            }
        }
        return null;
    }

    public function updateOrderAttachment(Request $request, $id)
    {
        $order = Order::findorfail($id);

        if ($request->hasFile('order_attach')) {
            $fileNameWithExt = $request->file('order_attach')->getClientOriginalName();
            $fileName = pathinfo($fileNameWithExt, PATHINFO_FILENAME);
            $extension = $request->file('order_attach')->getClientOriginalExtension();
            $fileNameToStore = $fileName . '.' . $extension;
            //delete previous file attachment
            unlink(storage_path('app/public/order/' . $order->id . '/order-attachment/' . $order->order_original_filename));
            $path = $request->file('order_attach')->storeAs('public/order/' . $order->id . '/order-attachment/', $fileNameToStore);
            $document_path = 'public/order/' . $order->id . '/order-attachment/' . $fileNameToStore;
            $order->order_original_filename = $fileNameToStore;
            $order->order_document_path = $document_path;
            $order->save();
        }
        return redirect()->action('OrderController@show', ['order' => $order->id])
            ->with(['status' => true, 'message' => 'Order Attachment Updated Sucessfully !']);
    }

    public function dispense_order(Order $order)
    {
        $order->status_id = 3;
        $order->save();
        return redirect()->action('OrderController@show', ['order' => $order->id])
            ->with(['status' => true, 'message' => 'Status = Dispense Order']);
    }

    public function complete_order(Order $order)
    {
        $order->status_id = 4;

        if ($order->dispensing_method == "Delivery") {
            if (!empty($order->delivery->delivered_date)) {
                $order->save();
            } else {
                return redirect()->action('OrderController@show', ['order' => $order->id])
                    ->with(['status' => false, 'message' => 'Please fill in Delivery Date']);
            }
        } else {
            $order->save();
        }

        return redirect()->action('OrderController@show', ['order' => $order->id])
            ->with(['status' => true, 'message' => 'Status = Complete Order']);
    }

    public function return_order(Order $order)
    {
        $order->status_id = 6;
        $order->return_timestamp = Carbon::now();
        $order->save();
        $order_items = OrderItem::where('order_id', $order->id)->get();

        foreach ($order_items as $oi) {
            $location = Location::where('item_id', $oi->myob_product_id)->first();
            if ($order->dispensing_method == 'Walkin') {
                $location->counter = $location->counter + $oi->quantity;
                $location->save();
            } elseif ($order->dispensing_method == 'Delivery') {
                $location->courier = $location->courier + $oi->quantity;
                $location->save();
            } else {
                echo 'error encountered';
            }
            $location->save();

            $stock = new Stock();
            $stock->item_id = $oi->myob_product_id;
            $stock->quantity = $oi->quantity;
            $stock->balance = 0;
            $stock->source = 'return';
            $stock->source_id = $oi->id;
            $stock->source_date = Carbon::now()->format('Y-m-d');
            $stock->save();
        }
        return redirect()->action('OrderController@show', ['order' => $order->id])
            ->with(['status' => true, 'message' => 'Status = Return Order']);
    }

    public function return_order_item($order, $order_id)
    {
        $order_item = OrderItem::where('id', $order_id)->first();
        $order = Order::where('id', $order_item->order_id)->first();
        $location = Location::where('item_id', $order_item->myob_product_id)->first();

        if ($order->dispensing_method == 'Walkin') {
            $location->counter = $location->counter + $order_item->quantity;
            $location->save();
        } elseif ($order->dispensing_method == 'Delivery') {
            $location->courier = $location->courier + $order_item->quantity;
            $location->save();
        } else {
            return 'hye';
        }
        $order->total_amount = $order->total_amount - $order_item->price;
        $order->save();
        $stock = new Stock();
        $stock->item_id = $order_item->myob_product_id;
        $stock->quantity = $order_item->quantity;
        $stock->balance = 0;
        $stock->source = 'return';
        $stock->source_id = $order_item->id;
        $stock->source_date = Carbon::now()->format('Y-m-d');
        $stock->save();

        $order_item->delete();
        return redirect()->action('OrderController@show', ['order' => $order->id])
            ->with(['status' => true, 'message' => 'Item Returned Successfully!']);
    }

    public function resubmission($id)
    {
        $prev_order = Order::where('id', $id)->first();
        $items = Item::all();
        $order = new Order();
        if (!empty($prev_order)) {
            $order->patient_id = $prev_order->patient_id;
            $order->status_id = 2;
            $order->dispensing_by = $prev_order->dispensing_by;
            $order->dispensing_method = $prev_order->dispensing_method;
            $order->rx_interval = 2;
            $order->salesperson_id = $prev_order->salesperson_id;
            $order->save();

            $prev_order->rx_interval = 3;
            $prev_order->save();

            if (!empty($prev_order->delivery)) {
                $delivery = new Delivery();
                $delivery->order_id = $order->id;
                $delivery->states_id = $prev_order->delivery->states_id;
                $delivery->address_1 = $prev_order->delivery->address_1;
                $delivery->address_2 = $prev_order->delivery->address_2;
                $delivery->postcode = $prev_order->delivery->postcode;
                $delivery->city = $prev_order->delivery->city;
                $delivery->save();
            }
            if (!empty($prev_order->prescription)) {
                $prescription = new Prescription();
                $prescription->order_id = $order->id;
                $prescription->clinic_id = $prev_order->prescription->clinic_id;
                $prescription->hospital_id = $prev_order->prescription->hospital_id;
                $prescription->rx_number = $prev_order->prescription->rx_number;
                $prescription->rx_original_filename = $prev_order->prescription->rx_original_filename;
                $prescription->rx_document_path = $prev_order->prescription->rx_document_path;
                $prescription->rx_start = $prev_order->prescription->rx_start;
                $prescription->rx_end = $prev_order->prescription->rx_end;
                $prescription->save();
            }
        }

        $item_lists = [];
        foreach ($items as $item) {
            $location = DB::table('locations')->where('item_id', $item->id)->first();
            if ($order->dispensing_method == "Walkin") {
                array_push($item_lists, [
                    'id' => $item->id,
                    'brand_name' => $item->brand_name,
                    'code' => $item->item_code,
                    'quantity' => $location->counter != null ? $location->counter : 0,
                ]);
            } else {
                array_push($item_lists, [
                    'id' => $item->id,
                    'brand_name' => $item->brand_name,
                    'code' => $item->item_code,
                    'quantity' => $location->courier != null ? $location->courier : 0,
                ]);
            }
        }

        $prev_order_item = OrderItem::where('order_id', $id)->get();
        foreach ($prev_order_item as $item) {
            $location = Location::where('item_id', $item->myob_product_id)->first();
            if ($order->dispensing_method == 'Walkin' && $location->counter >= $item->quantity) {
                $location->counter = $location->counter - $item->quantity;
                $location->save();

                $record = new OrderItem();
                $record->order_id = $order->id;
                $record->myob_product_id = $item->myob_product_id;
                $record->dose_quantity = $item->dose_quantity;
                $record->duration = $item->duration;
                $record->frequency = $item->frequency;
                $record->quantity = $item->quantity;
                $record->price = $item->price;
                $record->save();

                $stock = new Stock();
                $stock->item_id = $item->myob_product_id;
                $stock->quantity = -$item->quantity;
                $stock->balance = 0;
                $stock->source = 'sale';
                $stock->source_id = $record->id;
                $stock->source_date = Carbon::now()->format('Y-m-d');
                $stock->save();
            } elseif ($order->dispensing_method == 'Delivery' && $location->courier >= $item->quantity) {
                $location->courier = $location->courier - $item->quantity;
                $location->save();

                $record = new OrderItem();
                $record->order_id = $order->id;
                $record->myob_product_id = $item->myob_product_id;
                $record->dose_quantity = $item->dose_quantity;
                $record->duration = $item->duration;
                $record->frequency = $item->frequency;
                $record->quantity = $item->quantity;
                $record->price = $item->price;
                $record->save();

                $stock = new Stock();
                $stock->item_id = $item->myob_product_id;
                $stock->quantity = -$item->quantity;
                $stock->balance = 0;
                $stock->source = 'sale';
                $stock->source_id = $record->id;
                $stock->source_date = Carbon::now()->format('Y-m-d');
                $stock->save();
            } else {
            }
        }
        return redirect()->action('OrderController@new_resubmission', ['order' => $order->id]);
    }

    public function new_resubmission($id)
    {
        $states = State::all();
        $hospitals = Hospital::all();
        $salesPersons = SalesPerson::all();
        $clinics = Clinic::all();
        $frequencies = Frequency::all();
        $order = Order::where('id', $id)->first();
        $items = Item::all();
        // $items = DB::table('myob_products as a')->join('myob_product_details as b', 'b.myob_product_id', 'a.ItemNumber')->where('IsInactive', 'N')->get();
        $item_lists = OrderItem::where('order_id', $id)->get();
        $resubmission = 1;
        $roles = DB::table('model_has_roles')->join('users', 'model_has_roles.model_id', '=', 'users.id')->where("users.id", auth()->id())->first();
        return view('orders.edit', compact(
            'states',
            'hospitals',
            'salesPersons',
            'clinics',
            'frequencies',
            'order',
            'items',
            'item_lists',
            'resubmission',
            'roles'
        ));
    }

    public function print_invoice()
    {
        return view('print.print2');
    }

    public function download_invoice($id)
    {
        $order = Order::where('id', $id)->first();
        $date = Carbon::now()->format('d/m/Y');
        $pdf = PDF::loadView('print.print2', compact('order', 'date'));
        return $pdf->stream('downloadInvoice.pdf');
    }

    public function print_do()
    {
        return view('print.print3');
    }

    public function download_do($id)
    {
        $order = Order::where('id', $id)->first();
        $date = Carbon::now()->format('d/m/Y');
        $pdf = PDF::loadView('print.print3', compact('order', 'date'));
        return $pdf->stream('downloadDO.pdf');
    }
    public function delivery_status(Request $request, $order)
    {
        if (!$request->input('status')) {
            return back()->with(['status' => false, 'message' => 'Please tick the checkbox']);
        } else {
            $delivery = Delivery::where('order_id', $order)->first();
            $delivery->status = $request->input('status');
            $delivery->delivered_date = $request->input('date');
            $delivery->save();
            return back()->with(['status' => true, 'message' => 'Update successfully']);
        }
    }
}
