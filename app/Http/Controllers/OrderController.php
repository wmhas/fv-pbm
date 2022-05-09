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
use App\Models\BatchOrder;
use App\Models\Log\InventoryLog;
use App\Models\Log\OrderDateLog;
use App\Models\Log\OrderItemLog;
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
                ->whereHas('patient', function ($patient) use ($keyword) {
                    $patient->where('full_name', 'like', '%' . strtoupper($keyword) . '%');
                })
                ->orWhere('do_number', 'like', '%' . strtoupper($keyword) . '%')
                ->orderBy('status_id', 'asc')
                ->orderBy('created_at', 'desc')->limit(500)
                ->paginate(15);
        } elseif ($method == null && $status_id != null && $keyword != null) {
            $orders = Order::with('prescription')->with('patient')->with('delivery')->where('status_id', 'like', '%' . strtoupper($status_id) . '%')
                ->whereHas('patient', function ($patient) use ($keyword) {
                    $patient->where('full_name', 'like', '%' . strtoupper($keyword) . '%');
                })
                ->orWhere('do_number', 'like', '%' . strtoupper($keyword) . '%')
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
            $orders = Order::with('prescription')->with('patient')->with('delivery')
                ->whereHas('patient', function ($patient) use ($keyword) {
                    $patient->where('full_name', 'like', '%' . strtoupper($keyword) . '%');
                })
                ->orWhere('do_number', 'like', '%' . strtoupper($keyword) . '%')
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
        if ($order->dispensing_method == '0' && $order->rx_interval == '0' && $order->total_amount == '0') {
            return redirect()->action('OrderController@create_order', [
                'order_id' => $order->id,
                'patient' => $order->patient_id
            ]);
        } elseif ($order->dispensing_method != '0' && $order->rx_interval == '0' && $order->total_amount == '0') {
            return redirect()->action('OrderController@create_prescription', [
                'order_id' => $order->id,
                'id' => $order->patient_id
            ]);
        } elseif ($order->dispensing_method != '0' && $order->rx_interval != '0' && $order->total_amount == '0') {
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
        $orderItemSelected = [];
        foreach ($order->orderItem as $key => $value) {
            $orderItemSelected[] = DB::table('items as a')
            ->join('frequencies as b', 'b.id', 'a.frequency_id')
            ->join('formulas as c', 'c.id', 'a.formula_id')
            ->select('a.id', 'a.selling_price as selling_price', 'a.selling_uom as selling_uom', 'a.instruction', 'a.indikasi as indication', 'a.formula_id', 'b.name', 'b.id as freq_id', 'c.value')
            ->where('a.id', $value->items->id)
            ->first();
        }


        $prescription = Prescription::select('rx_start', 'rx_end', 'next_supply_date')->where('order_id', $order->id)->first();

        $duration = $this->getDuration($order, $prescription);

        
        $frequencies = Frequency::all();
        $resubmission = 0;
        $roles = DB::table('model_has_roles')->join('users', 'model_has_roles.model_id', '=', 'users.id')->where("users.id", auth()->id())->first();
        return view('orders.edit', compact('states', 'hospitals', 'clinics', 'salesPersons', 'order', 'items', 'item_lists', 'frequencies', 'roles', 'resubmission','duration', 'orderItemSelected'));
    }

    public function store_edit($id, Request $request)
    {
        $do_number = $request->input('do_number');
        $exists = Order::where('do_number', $do_number)->whereNull('deleted_at')->count();
        $order = Order::where('id', $id)->first();

        if ($exists > 0) {
            if ($order->do_number != $do_number)
                return back()->with(['status' => false, 'message' => 'DO number ' . $do_number . ' already exist.']);
        }

        if ($request->dispensing_method != $order->dispensing_method) {
            if ($request->dispensing_method == "Walkin") {
                foreach ($order->orderItem as $order_item) {
                    $current_stock = Location::where('item_id', $order_item->myob_product_id)->first()->counter;
                    if ($current_stock < $order_item->quantity) {
                        return back()->with(['status' => false, 'message' => 'Insufficient stock balance for item ' . $order_item->items->brand_name . '. Cannot change dispensing method.']);
                    }
                }

                foreach ($order->orderItem as $order_item) {
                    $location = Location::where('item_id', $order_item->myob_product_id)->first();
                    $location->counter = $location->counter - $order_item->quantity;
                    $location->courier = $location->courier + $order_item->quantity;
                    $location->save();
                }

                $delivery = Delivery::where('order_id', $order->id)->first();
                $delivery->delete();
            } 
            
            if ($request->dispensing_method == 'Delivery') {
                foreach ($order->orderItem as $order_item) {
                    $current_stock = Location::where('item_id', $order_item->myob_product_id)->first()->courier;
                    if ($current_stock < $order_item->quantity) {
                        return back()->with(['status' => false, 'message' => 'Insufficient stock balance for item ' . $order_item->items->brand_name . '. Cannot change dispensing method.']);
                    }
                }

                foreach ($order->orderItem as $order_item) {
                    $location = Location::where('item_id', $order_item->myob_product_id)->first();
                    $location->counter = $location->counter + $order_item->quantity;
                    $location->courier = $location->courier - $order_item->quantity;
                    $location->save();
                }
            }
        }
            
        $order->salesperson_id = $request->input('salesperson');
        if ($order->status_id == 1)
            $order->status_id = $order->status_id + 1;
        $order->do_number = $do_number;
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
            $prescription->next_supply_date = $request->input('rx_supply_date');
            $prescription->save();
        } else {
            $order->prescription->hospital_id = $request->input('rx_hospital');
            $order->prescription->clinic_id = $request->input('rx_clinic');
            $order->prescription->rx_number = $request->input('rx_number');
            $order->prescription->rx_start = $request->input('rx_start_date');
            $order->prescription->rx_end = $request->input('rx_end_date');
            $order->prescription->next_supply_date = $request->input('rx_supply_date');
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

        $order = Order::where('patient_id', $patient)->where('do_number', '')->first();
        $salesPersons = SalesPerson::all();
        $roles = DB::table('model_has_roles')->join('users', 'model_has_roles.model_id', '=', 'users.id')->where("users.id", auth()->id())->first();

        if (empty($order)) {
            $order = Order::where('id', $order)->first();
            
            if ($order == null) {
                $order = new Order();
                $order->patient_id = $patient;
                $order->total_amount = 0;
                $order->status_id = 1;
                $order->dispensing_method = 1;
                $order->rx_interval = 0;
                $order->save();
            }
            
            return view('orders.create.create_order1', compact('order', 'states', 'roles', 'salesPersons'));
        } else {
            return view('orders.create.create_order1', compact('order', 'states', 'roles', 'salesPersons'));
        }

        
    }

    public function store_dispense($patient, $order_id, Request $request)
    {
        $do_number = $request->input('do_number');
        $exists = Order::where('do_number', $do_number)->whereNull('deleted_at')->count();

        while ($exists > 0) {
            $do_number = $this->getDONumber();
            $exists = Order::where('do_number', $do_number)->whereNull('deleted_at')->count();
        }

        $order = Order::where('id', $order_id)->first();
        $order->salesperson_id = $request->input('salesperson');
        $order->do_number = $do_number;
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
        $roles = DB::table('model_has_roles')->join('users', 'model_has_roles.model_id', '=', 'users.id')->where("users.id", auth()->id())->first();
        return view('orders.create.create_order2', compact('order', 'hospitals', 'clinics', 'roles'));
    }

    public function store_prescription($patient, $order_id, Request $request)
    {
        $order = Order::where('id', $order_id)->whereNull('orders.deleted_at')->first();
        if(empty($request->input('rx_interval'))){
            $order->rx_interval = 2;
        }else{
            $order->rx_interval = $request->input('rx_interval');
        }
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

    private function getDuration($order, $prescription){

        $duration = 0;

        if (isset($order) && isset($prescription)) {

            if ($order->rx_interval==1) {
                $duration = floor(abs(strtotime($prescription->rx_end) - strtotime($prescription->rx_start)) / (60 * 60 * 24));
            } else if ($order->rx_interval == 2 && $order->do_number != NULL) {
                $duration = floor(abs(strtotime($prescription->next_supply_date) - strtotime($prescription->rx_start)) / (60 * 60 * 24)) + 1;
            } else if ($order->rx_interval == 2 && $order->do_number == NULL) {
                $duration = floor(abs(strtotime($prescription->rx_end) - strtotime($prescription->next_supply_date)) / (60 * 60 * 24));
            }

        }

        return $duration;
    }

    public function create_orderEntry($patient, $order_id)
    {
        ini_set('max_execution_time', 300);
        
        $items = Item::select('id','brand_name','item_code', 'frequency_id')->get();
        $order = Order::where('id', $order_id)->first();

        $orderItems = $order->orderitem;

        $prescription = Prescription::select('rx_start', 'rx_end', 'next_supply_date')->where('order_id', $order_id)->first();

        $duration = $this->getDuration($order, $prescription);

        $item_lists = [];
        foreach ($items as $item) {
            $location = DB::table('locations')->select('counter','courier')->where('item_id', $item->id)->first();

            if ($order->dispensing_method == "Walkin") {
                array_push($item_lists, [
                    'id' => $item->id,
                    'brand_name' => $item->brand_name,
                    'code' => $item->item_code,
                    'quantity' => $location->counter != null ? $location->counter : 0,
                    'frequency' => $item->frequency_id,
                ]);
            } else {
                array_push($item_lists, [
                    'id' => $item->id,
                    'brand_name' => $item->brand_name,
                    'code' => $item->item_code,
                    'quantity' => $location->courier != null ? $location->courier : 0,
                    'frequency' => $item->frequency_id,
                ]);
            }
        }
        $frequencies = Frequency::all();

        $roles = DB::table('model_has_roles')->join('users', 'model_has_roles.model_id', '=', 'users.id')->where("users.id", auth()->id())->first();
        return view('orders.create.create_order3', compact('order', 'orderItems', 'item_lists', 'roles', 'frequencies', 'duration'));
    }

    public function store_orderEntry($patient, $order_id, Request $request)
    {
        $order = Order::where('id', $order_id)->first();
        
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

    public function store_item(Request $request)
    {
        $order = Order::select("id", "do_number", "dispensing_method","patient_id", "total_amount")->where('id',  $request->input('order_id'))->first();
        $location = Location::where('item_id', $request->input('item_id'))->first();
        $item = Item::where('id', $request->input('item_id'))->first();
        
        // log inventory
        $log = new InventoryLog();
        $log->process = "Store item for order " . $order->id . " " . $order->do_number;

        $log->item_id = $item->id;
        $log->item_name = $item->brand_name;

        $log->stock_before = $item->stocks->sum('Quantity');
        $log->store_before = $location->store;
        $log->counter_before = $location->counter;
        $log->courier_before = $location->courier;
        $log->loan_before = $location->staff;
        
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
        $record->selling_price = $item->selling_price;
        $record->price = $record->quantity * $record->selling_price;
        $record->save();

        $log = new OrderItemLog;
        $log->process = "Add Item";
        $log->order_id = $order->id;
        $log->order_item_id = $record->id;
        $log->item_id = $record->myob_product_id;
        $log->item_name = $record->items->brand_name;
        $log->stored_selling_price = $record->items->selling_price;
        $log->dose_quantity = $request->input('dose_quantity');
        $log->duration = $request->input('duration');
        $log->frequency = $request->input('frequency');
        $log->frequency_name = $record->frequencies->name;
        $log->input_quantity = $request->input('quantity');
        $log->input_price = $request->input('price');
        $log->input_selling_price = $request->selling_price;
        

        $formula_id = $record->items->formula_id;

        if($formula_id == 1){
            $log->calculated_quantity = $log->dose_quantity * $log->frequency * $log->duration;
        }

        elseif($formula_id == 2){
            $log->calculated_quantity = ($log->dose_quantity * $log->frequency * $log->duration) / 120;
        }

        elseif($formula_id == 3){
            $log->calculated_quantity = ($log->dose_quantity * $log->frequency * $log->duration) / 30;
        }
        elseif($formula_id == 4){
            $log->calculated_quantity = ($log->dose_quantity * $log->frequency * $log->duration) / 60;
        }
        elseif($formula_id == 5){
            $log->calculated_quantity = ($log->dose_quantity * $log->frequency * $log->duration) / 300;
        }
        else{
            $log->calculated_quantity = 1;
        }

        $log->calculated_price = $log->calculated_quantity*$log->stored_selling_price;
        LogController::writeOrderItemLog($log);

        $stock = new Stock();
        $stock->item_id = $request->input('item_id');
        $stock->quantity = -$request->input('quantity');
        $stock->balance = 0;
        $stock->source = 'sale';
        $stock->source_id = $record->id;
        $stock->source_date = Carbon::now()->format('Y-m-d');
        $stock->save();

        if ($order->total_amount != 0) {
            $this->calculateTotalAmount($order->id);
        }

        // log inventory
        $item = Item::where('id', $request->input('item_id'))->first();
        $location = Location::where('item_id', $request->input('item_id'))->first();
        $log->stock_after = $item->stocks->sum('Quantity');
        $log->store_after = $location->store;
        $log->counter_after = $location->counter;
        $log->courier_after = $location->courier;
        $log->loan_after = $location->staff;

        $log->stock_changes = $stock->quantity;
        $log->store_changes = $log->store_after - $log->store_before;
        $log->counter_changes = $log->counter_after - $log->counter_before;
        $log->courier_changes = $log->courier_after - $log->courier_before;
        $log->loan_changes = $log->loan_after - $log->loan_before;
        LogController::writeInventoryLog($log);

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

    private function getDONumber($dispensing_by = null)
    {
        $increment = 1;
     
        do {
            $count_order = DB::table('orders')->where('do_number', '!=', '')->whereNull('deleted_at')->count();
            $do_number = str_pad($count_order + $increment, 8, "0", STR_PAD_LEFT);

            $exists = Order::where('do_number', $do_number)->first();

            if ($exists)
                $increment++;

        } while ($exists);

        return $do_number;
    }

    public function store_item_resubmission(Request $request)
    {

        $count = count($request->input('item_id'));
        $parentOrder = "";

        DB::beginTransaction();

        try {

            $order = Order::where('id',  $request->input('order_id')[0])->first();
            $od = new Order; 
            $od->patient_id = $order->patient_id;
            $od->total_amount = $order->total_amount;
            $od->do_number = "";
            $od->dispense_date = null;
            $od->dispensing_method = $request->dispensing_method;
            $od->rx_interval = 1;
            $od->salesperson_id = $order->salesperson_id;
            $od->order_document_path = $order->order_document_path;
            $od->order_original_filename = $order->order_original_filename;
            $od->total_amount = $order->total_amount;
            $od->status_id = 1;
            $od->dispensing_by = $order->dispensing_by;
            $od->resubmission = 1;
            $od->parent = $order->id;
            $od->save();

            if ($order->prescription) {
                $pre = new Prescription;
                $pre->order_id = $od->id;
                $pre->clinic_id = $order->prescription->clinic_id;
                $pre->hospital_id = $order->prescription->hospital_id;
                $pre->rx_number = $order->prescription->rx_number;
                $pre->rx_original_filename = $order->prescription->rx_original_filename;
                $pre->rx_document_path = $order->prescription->rx_document_path;
                $pre->rx_start = $order->prescription->rx_start;
                $pre->rx_end = $order->prescription->rx_end;
                $pre->next_supply_date = $order->prescription->next_supply_date;
                $pre->save();
            }

            if ($request->dispensing_method=="Delivery"){
                
                $delivery = new Delivery;
                $delivery->order_id = $od->id;

                if (!empty($order->delivery)) {

                    $delivery->states_id = $order->delivery->states_id;
                    $delivery->address_1 = $order->delivery->address_1;
                    $delivery->address_2 = $order->delivery->address_2;
                    $delivery->postcode = $order->delivery->postcode;
                    $delivery->city = $order->delivery->city;

                } else {

                    $delivery->states_id = $order->patient->state_id;
                    $delivery->address_1 = $order->patient->address_1;
                    $delivery->address_2 = $order->patient->address_2;
                    $delivery->postcode = $order->patient->postcode;
                    $delivery->city = $order->patient->city;

                }
                $delivery->save();
            }

            for ($i=0; $i < $count; $i++) {

                $order_id = $od->id;

                $location = Location::where('item_id', $request->input('item_id')[$i])->first();
                $item = Item::where('id', $request->input('item_id')[$i])->first();

                // log inventory
                $log = new InventoryLog();
                $log->process = "Add Item Resubmission - parent ID: " . $od->parent . " Order ID : " . $od->id;
                $log->item_id = $item->id;
                $log->item_name = $item->brand_name;

                $log->stock_before = $item->stocks->sum('Quantity');
                $log->store_before = $location->store;
                $log->counter_before = $location->counter;
                $log->courier_before = $location->courier;
                $log->loan_before = $location->staff;


                if ($request->dispensing_method == 'Walkin' && $location->counter >= $request->input('quantity')[$i]) {
                    $location->counter = $location->counter - $request->input('quantity')[$i];
                    $location->save();
                } elseif ($request->dispensing_method == 'Delivery' && $location->courier >= $request->input('quantity')[$i]) {
                    $location->courier = $location->courier - $request->input('quantity')[$i];
                    $location->save();
                } else {
                    DB::rollback();

                    if ($request->parent){
                        $parentOrder = "?parent=".$request->parent;
                    }

                    if ($request->dispensing_method){
                        $parentOrder = $parentOrder."&sdm=".$request->dispensing_method;
                    }

                    return redirect('order/'.$order->id.'/new_resubmission'.$parentOrder)->with(['status' => false, 'message' => 'Item quantity exceeded the number of quantity available']);
                }

                $record = new OrderItem();
                $record->order_id = $order_id;
                $record->myob_product_id = $request->input('item_id')[$i];
                $record->dose_quantity = $request->input('dose_quantity')[$i];
                $record->duration = $request->input('duration')[$i];
                $record->frequency = $request->input('frequency')[$i];
                $record->quantity = $request->input('quantity')[$i];
                $record->selling_price = $item->selling_price;
                $record->price = $record->quantity * $record->selling_price;
                $record->save();

                $log = new OrderItemLog;
                $log->process = "Add Item Resubmission";
                $log->order_id = $order->id;
                $log->order_item_id = $record->id;
                $log->item_id = $record->myob_product_id;
                $log->item_name = $record->items->brand_name;
                $log->stored_selling_price = $record->items->selling_price;
                $log->dose_quantity = $request->input('dose_quantity')[$i];
                $log->duration = $request->input('duration')[$i];
                $log->frequency = $request->input('frequency')[$i];
                $log->frequency_name = $record->frequencies->name;
                $log->input_quantity = $request->input('quantity')[$i];
                $log->input_price = $request->input('price')[$i];
                $log->input_selling_price = $request->selling_price[$i];
                
        
                $formula_id = $record->items->formula_id;
        
                if($formula_id == 1){
                    $log->calculated_quantity = $log->dose_quantity * $log->frequency * $log->duration;
                }
    
                elseif($formula_id == 2){
                    $log->calculated_quantity = ($log->dose_quantity * $log->frequency * $log->duration) / 120;
                }
    
                elseif($formula_id == 3){
                    $log->calculated_quantity = ($log->dose_quantity * $log->frequency * $log->duration) / 30;
                }
                elseif($formula_id == 4){
                    $log->calculated_quantity = ($log->dose_quantity * $log->frequency * $log->duration) / 60;
                }
                elseif($formula_id == 5){
                    $log->calculated_quantity = ($log->dose_quantity * $log->frequency * $log->duration) / 300;
                }
                else{
                    $log->calculated_quantity = 1;
                }

                $log->calculated_price = $log->calculated_quantity*$log->stored_selling_price;
                LogController::writeOrderItemLog($log);
        
                $stock = new Stock();
                $stock->item_id = $request->input('item_id')[$i];
                $stock->quantity = -$request->input('quantity')[$i];
                $stock->balance = 0;
                $stock->source = 'sale';
                $stock->source_id = $record->id;
                $stock->source_date = Carbon::now()->format('Y-m-d');
                $stock->save();

                $location = Location::where('item_id', $request->input('item_id')[$i])->first();
                $item = Item::where('id', $request->input('item_id')[$i])->first();

                // log inventory
                $log->stock_after = $item->stocks->sum("Quantity");
                $log->store_after = $location->store;
                $log->counter_after = $location->counter;
                $log->courier_after = $location->courier;
                $log->loan_after = $location->staff;

                $log->stock_changes = $stock->quantity;
                $log->store_changes = $log->store_after - $log->store_before;
                $log->counter_changes = $log->counter_after - $log->counter_before;
                $log->courier_changes = $log->courier_after - $log->courier_before;
                $log->loan_changes = $log->loan_after - $log->loan_before;
                LogController::writeInventoryLog($log);

            }
            DB::commit();

            if ($request->parent){
                $parentOrder = "?parent=".$request->parent."&item=added";
            } else {
                $parentOrder = "?item=added";
            }

            if ($request->dispensing_method){
                $parentOrder = $parentOrder."&sdm=".$request->dispensing_method;
            }

            return redirect('order/'.$order_id.'/new_resubmission'.$parentOrder)->with(['status' => true, 'message' => 'Successfully add item']);

        } catch (\Exception $e) {
            DB::rollback();
            dd($e);
        }
    }

    public function update_item(Request $request)
    {
        $order_item = OrderItem::where('id', $request->input('order_item_id'))->first();
        $order = Order::where('id', $order_item->order_id)->first();
        $location = Location::where('item_id', $order_item->myob_product_id)->first();
        $item = Item::find($order_item->myob_product_id);

        // log inventory

        $log = new InventoryLog();
        $log->process = "Edit item for order " .$order->id . " " . $order->do_number;

        $log->item_id = $item->id;
        $log->item_name = $item->brand_name;

        $log->stock_before = $item->stocks->sum('Quantity');
        $log->store_before = $location->store;
        $log->counter_before = $location->counter;
        $log->courier_before = $location->courier;
        $log->loan_before = $location->staff;

        if ($order->dispensing_method == 'Walkin' && ($location->counter + $order_item->quantity) >= $request->input('quantity')) {
            $location->counter = $location->counter - $request->input('quantity') + $order_item->quantity;
            $location->save();
        } elseif ($order->dispensing_method == 'Delivery' && ($location->courier + $order_item->quantity) >= $request->input('quantity')) {
            $location->courier = $location->courier - $request->input('quantity') + $order_item->quantity;
            $location->save();
        } else {
            if ($order->status_id == 1)
                return redirect()->action('OrderController@create_orderEntry', ['patient' => $order->patient_id, 'order_id', $order->id])->with(['status' => false, 'message' => 'Item quantity exceeded the number of quantity available']);
            else
                return back()->with(['status' => false, 'message' => 'Item quantity exceeded the number of quantity available']);
        }

        $record = OrderItem::find($order_item->id);
        $record->order_id = $request->input('order_id');
        $record->myob_product_id = $request->input('item_id');
        $record->dose_quantity = $request->input('dose_quantity');
        $record->duration = $request->input('duration');
        $record->frequency = $request->input('frequency');
        $record->quantity = $request->input('quantity');
        $record->selling_price = $item->selling_price;
        $record->price = $record->selling_price * $record->quantity;
        $record->save();

        $item = Item::find($order_item->myob_product_id);
        $item->frequency_id = $request->input('frequency');
        $item->save();

        $stock = new Stock();
        $stock->item_id = $order_item->myob_product_id;
        $stock->quantity = $order_item->quantity;
        $stock->balance = 0;
        $stock->source = 'edit';
        $stock->source_id = $order_item->id;
        $stock->source_date = Carbon::now()->format('Y-m-d');
        $stock->save();

        $stock = new Stock();
        $stock->item_id = $request->input('item_id');
        $stock->quantity = -$request->input('quantity');
        $stock->balance = 0;
        $stock->source = 'sale';
        $stock->source_id = $record->id;
        $stock->source_date = Carbon::now()->format('Y-m-d');
        $stock->save();

        $this->calculateTotalAmount($order->id);

        // log inventory
        $item = Item::where('id', $item->id)->first();
        $location = Location::where('item_id', $item->id)->first();
        $log->stock_after = $item->stocks->sum('Quantity');
        $log->store_after = $location->store;
        $log->counter_after = $location->counter;
        $log->courier_after = $location->courier;
        $log->loan_after = $location->staff;

        $log->stock_changes = $order_item->quantity - $request->input('quantity');
        $log->store_changes = $log->store_after - $log->store_before;
        $log->counter_changes = $log->counter_after - $log->counter_before;
        $log->courier_changes = $log->courier_after - $log->courier_before;
        $log->loan_changes = $log->loan_after - $log->loan_before;
        LogController::writeInventoryLog($log);

        $log = new OrderItemLog;
        $log->process = "Update Item";
        $log->order_id = $order->id;
        $log->order_item_id = $record->id;
        $log->item_id = $record->myob_product_id;
        $log->item_name = $record->items->brand_name;
        $log->stored_selling_price = $record->items->selling_price;
        $log->dose_quantity = $request->input('dose_quantity');
        $log->duration = $request->input('duration');
        $log->frequency = $request->input('frequency');
        $log->frequency_name = $record->frequencies->name;
        $log->input_quantity = $request->input('quantity');
        $log->input_price = $request->input('price');
        $log->input_selling_price = $request->selling_price;                

        $formula_id = $record->items->formula_id;

        if($formula_id == 1){
            $log->calculated_quantity = $log->dose_quantity * $log->frequency * $log->duration;
        }

        elseif($formula_id == 2){
            $log->calculated_quantity = ($log->dose_quantity * $log->frequency * $log->duration) / 120;
        }

        elseif($formula_id == 3){
            $log->calculated_quantity = ($log->dose_quantity * $log->frequency * $log->duration) / 30;
        }
        elseif($formula_id == 4){
            $log->calculated_quantity = ($log->dose_quantity * $log->frequency * $log->duration) / 60;
        }
        elseif($formula_id == 5){
            $log->calculated_quantity = ($log->dose_quantity * $log->frequency * $log->duration) / 300;
        }
        else{
            $log->calculated_quantity = 1;
        }
        
        $log->calculated_price = $log->calculated_quantity*$log->stored_selling_price;
        LogController::writeOrderItemLog($log);


        if ($order->total_amount == 0) {
            return redirect()->route('order.entry', [
                'id' => $request->input('patient_id'),
                'order_id' => $request->input('order_id')
            ])->with(['status' => true, 'message' => 'Successfully update item']);
        } else {
            return redirect()->route('order.update', [
                'order' => $request->input('order_id')
            ])->with(['status' => true, 'message' => 'Successfully update item']);
        }

       
    }

    public function delete_item($patient, $id)
    {
        $order_item = OrderItem::where('id', $id)->first();
        $order = Order::where('id', $order_item->order_id)->first();
        $location = Location::where('item_id', $order_item->myob_product_id)->first();
        $item = Item::where('id', $order_item->myob_product_id)->first();

        // log inventory
        $log = new InventoryLog();
        $log->process = "Delete item for order " .$order->id . " " . $order->do_number;

        $log->item_id = $item->id;
        $log->item_name = $item->brand_name;

        $log->stock_before = $item->stocks->sum('Quantity');
        $log->store_before = $location->store;
        $log->counter_before = $location->counter;
        $log->courier_before = $location->courier;
        $log->loan_before = $location->staff;

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

            // log inventory
            $item = Item::where('id', $item->id)->first();
            $location = Location::where('item_id', $item->id)->first();
            $log->stock_after = $item->stocks->sum('Quantity');
            $log->store_after = $location->store;
            $log->counter_after = $location->counter;
            $log->courier_after = $location->courier;
            $log->loan_after = $location->staff;

            $log->stock_changes = $stock->quantity;
            $log->store_changes = $log->store_after - $log->store_before;
            $log->counter_changes = $log->counter_after - $log->counter_before;
            $log->courier_changes = $log->courier_after - $log->courier_before;
            $log->loan_changes = $log->loan_after - $log->loan_before;
            LogController::writeInventoryLog($log);

            $this->calculateTotalAmount($order->id);

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
            $stock->item_id = $oi->myob_product_id;
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
        $order->dispense_date = now();
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

            // log inventory
            $log = new InventoryLog();
            $log -> process = "Return order " . $order->id;
            $log -> item_id = $oi -> myob_product_id;
            $log -> item_name = $oi -> items -> brand_name;

            $log -> stock_before = $oi -> items -> stocks -> sum('Quantity');
            $log -> store_before = $location -> store;
            $log -> counter_before = $location -> counter;
            $log -> courier_before = $location -> courier;
            $log -> loan_before = $location -> staff;

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

            // log inventory
            $item = Item::where('id', $oi->myob_product_id)->first();
            $location = Location::where('item_id', $oi->myob_product_id)->first();
            $log->stock_after = $item->stocks->sum('Quantity');
            $log->store_after = $location->store;
            $log->counter_after = $location->counter;
            $log->courier_after = $location->courier;
            $log->loan_after = $location->staff;

            $log->stock_changes = $oi->quantity;
            $log->store_changes = $log->store_after - $log->store_before;
            $log->counter_changes = $log->counter_after - $log->counter_before;
            $log->courier_changes = $log->courier_after - $log->courier_before;
            $log->loan_changes = $log->loan_after - $log->loan_before;
            LogController::writeInventoryLog($log);
        }
        return redirect()->action('OrderController@show', ['order' => $order->id])
            ->with(['status' => true, 'message' => 'Status = Return Order']);
    }

    public function return_order_item($order, $order_id)
    {
        $order_item = OrderItem::where('id', $order_id)->first();
        $order = Order::where('id', $order_item->order_id)->first();
        $location = Location::where('item_id', $order_item->myob_product_id)->first();

        // log inventory
        $log = new InventoryLog();
        $log -> process = "Return item ";
        $log -> item_id = $order_item -> myob_product_id;
        $log -> item_name = $order_item -> items -> brand_name;

        $log -> stock_before = $order_item -> items -> stocks -> sum('Quantity');
        $log -> store_before = $location -> store;
        $log -> counter_before = $location -> counter;
        $log -> courier_before = $location -> courier;
        $log -> loan_before = $location -> staff;

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

        // log inventory
        $order_item = OrderItem::where('id', $order_id)->first();
        $location = Location::where('item_id', $order_item->myob_product_id)->first();
        $log->stock_after = $order_item->items->stocks->sum('Quantity');
        $log->store_after = $location->store;
        $log->counter_after = $location->counter;
        $log->courier_after = $location->courier;
        $log->loan_after = $location->staff;

        $log->stock_changes = $order_item->quantity;
        $log->store_changes = $log->store_after - $log->store_before;
        $log->counter_changes = $log->counter_after - $log->counter_before;
        $log->courier_changes = $log->courier_after - $log->courier_before;
        $log->loan_changes = $log->loan_after - $log->loan_before;
        LogController::writeInventoryLog($log);

        $order_item->delete();
        return redirect()->action('OrderController@show', ['order' => $order->id])
            ->with(['status' => true, 'message' => 'Item Returned Successfully!']);
    }

    public function resubmission(Request $request, $id)
    {
        $do_number = $request->input('do_number');
        $exists = Order::where('do_number', $do_number)->whereNull('deleted_at')->count();
        $prev_order = Order::where('id', $id)->first();
        $order = Order::find($prev_order->id);

        while ($exists > 0) {
            $do_number = $this->getDONumber();
            $exists = Order::where('do_number', $do_number)->whereNull('deleted_at')->count();
        }

        if ($request->parent){
            $up = Order::where('id', (int) $request->parent)->update(['rx_interval'=>3]);
        }

        if ($request->dispensing_method != $order->dispensing_method) {
            if ($request->dispensing_method == "Walkin") {
                foreach ($order->orderItem as $order_item) {
                    $current_stock = Location::where('item_id', $order_item->myob_product_id)->first()->counter;
                    if ($current_stock < $order_item->quantity) {
                        return back()->with(['status' => false, 'message' => 'Insufficient stock balance for item ' . $order_item->items->brand_name . '. Cannot change dispensing method.']);
                    }
                }

                foreach ($order->orderItem as $order_item) {
                    $location = Location::where('item_id', $order_item->myob_product_id)->first();

                    // log inventory
                    $log = new InventoryLog();
                    $log->process = "Save Order Walkin - Order ID : " . $order->id ;
                    $log->item_id = $order_item->items->id;
                    $log->item_name = $order_item->items->brand_name;

                    $log->store_before = $location->store;
                    $log->counter_before = $location->counter;
                    $log->courier_before = $location->courier;
                    $log->loan_before = $location->staff;

                    $location->counter = $location->counter - $order_item->quantity;
                    $location->courier = $location->courier + $order_item->quantity;
                    $location->save();

                    $location = Location::where('item_id', $order_item->myob_product_id)->first();

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
                }

                $delivery = Delivery::where('order_id', $order->id)->first();
                $delivery->delete();
            } 
            
            if ($request->dispensing_method == 'Delivery') {
                foreach ($order->orderItem as $order_item) {
                    $current_stock = Location::where('item_id', $order_item->myob_product_id)->first()->courier;
                    if ($current_stock < $order_item->quantity) {
                        return back()->with(['status' => false, 'message' => 'Insufficient stock balance for item ' . $order_item->items->brand_name . '. Cannot change dispensing method.']);
                    }
                }

                foreach ($order->orderItem as $order_item) {
                    $location = Location::where('item_id', $order_item->myob_product_id)->first();

                    // log inventory
                    $log = new InventoryLog();
                    $log->process = "Save Order Delivery - Order ID : " . $order->id ;
                    $log->item_id = $order_item->items->id;
                    $log->item_name = $order_item->items->brand_name;

                    $log->store_before = $location->store;
                    $log->counter_before = $location->counter;
                    $log->courier_before = $location->courier;
                    $log->loan_before = $location->staff;

                    $location->counter = $location->counter + $order_item->quantity;
                    $location->courier = $location->courier - $order_item->quantity;
                    $location->save();

                    $location = Location::where('item_id', $order_item->myob_product_id)->first();

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
                }
            }
        }
        
        
        $items = Item::all();  
        
        if (!empty($prev_order)) {
            $order->patient_id = $prev_order->patient_id;
            $order->status_id = 2;
            $order->dispensing_by = $request->input('dispensing_by');
            $order->dispensing_method = $request->input('dispensing_method');
            $order->rx_interval = $request->input('rx_interval');
            $order->do_number = $do_number;
            $order->salesperson_id = $request->input('salesperson');
            $order->total_amount = $request->input('total_amount');
            $order->save();

            if (!empty($prev_order->delivery)) {
                $delivery = Delivery::where("order_id",$order->id)->first();
                $delivery->states_id = ($request->dispensing_state) ? $request->dispensing_state : $prev_order->delivery->states_id;
                $delivery->address_1 = ($request->dispensing_add1) ? $request->dispensing_add1 : $prev_order->delivery->address_1;
                $delivery->address_2 = ($request->dispensing_add2) ? $request->dispensing_add2 : $prev_order->delivery->address_2;
                $delivery->postcode = ($request->dispensing_postcode) ? $request->dispensing_postcode : $prev_order->delivery->postcode;
                $delivery->city = ($request->dispensing_city) ? $request->dispensing_city : $prev_order->delivery->city;
                $delivery->method = ($request->delivery_method) ? $request->delivery_method : $prev_order->method;
                $delivery->tracking_number = ($request->tracking_number) ? $request->tracking_number : $prev_order->tracking_number;
                $delivery->send_date = ($request->send_date) ? $request->send_date : $prev_order->send_date;
                $delivery->save();
            }

            if (!empty($prev_order->prescription)) {
                $prescription = Prescription::where("order_id",$order->id)->first();
                $prescription->clinic_id = ($request->input('rx_clinic')) ? $request->input('rx_clinic') : $prev_order->prescription->clinic_id;
                $prescription->hospital_id = ($request->input('rx_hospital')) ? $request->input('rx_hospital') : $prev_order->prescription->hospital_id;
                $prescription->rx_number = ($request->input('rx_number')) ? $request->input('rx_number') : $prev_order->prescription->rx_number;
                $prescription->rx_start = ($request->input('rx_start_date')) ? $request->input('rx_start_date') : $prev_order->prescription->rx_start;
                $prescription->rx_end = ($request->input('rx_end_date')) ? $request->input('rx_end_date') : $prev_order->prescription->rx_end;
                
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

        return redirect()->action('OrderController@show', ['order' => $order->id])
                ->with(['status' => true, 'message' => 'Resubmission Success!']);
    }

    public function new_resubmission(Request $request, $id)
    {
        $states = State::all();
        $hospitals = Hospital::all();
        $salesPersons = SalesPerson::all();
        $clinics = Clinic::all();
        $frequencies = Frequency::all();
        $order = Order::where('id', $id)->first();
        $items = Item::all();
        
        $item_lists = [];
        foreach ($items as $item) {
            $location = DB::table('locations')->select('counter','courier')->where('item_id', $item->id)->first();

            if ($request->sdm) {
                if ($request->sdm == "Walkin") {
                    array_push($item_lists, [
                        'id' => $item->id,
                        'brand_name' => $item->brand_name,
                        'code' => $item->item_code,
                        'quantity' => $location->counter != null ? $location->counter : 0,
                        'frequency' => $item->frequency_id,
                    ]);
                } else {
                    array_push($item_lists, [
                        'id' => $item->id,
                        'brand_name' => $item->brand_name,
                        'code' => $item->item_code,
                        'quantity' => $location->courier != null ? $location->courier : 0,
                        'frequency' => $item->frequency_id,
                    ]);
                }
            } else {
                if ($order->dispensing_method == "Walkin") {
                    array_push($item_lists, [
                        'id' => $item->id,
                        'brand_name' => $item->brand_name,
                        'code' => $item->item_code,
                        'quantity' => $location->counter != null ? $location->counter : 0,
                        'frequency' => $item->frequency_id,
                    ]);
                } else {
                    array_push($item_lists, [
                        'id' => $item->id,
                        'brand_name' => $item->brand_name,
                        'code' => $item->item_code,
                        'quantity' => $location->courier != null ? $location->courier : 0,
                        'frequency' => $item->frequency_id,
                    ]);
                }
            }
        }

        $orderItemSelected = [];
        foreach ($order->orderItem as $key => $value) {
            $orderItemSelected[] = DB::table('items as a')
            ->join('formulas as c', 'c.id', 'a.formula_id')
            ->select('a.id', 'a.selling_price as selling_price', 'a.selling_uom as selling_uom', 'a.instruction', 'a.indikasi as indication', 'a.formula_id', 'c.value')
            ->where('a.id', $value->items->id)
            ->first();
            $orderItemSelected[$key]->freq_id = $value->frequencies->id;
            $orderItemSelected[$key]->name = $value->frequencies->name;
        }

        $resubmission = 1;
        
        $prescription = Prescription::select('rx_start', 'rx_end', 'next_supply_date')->where('order_id', $order->id)->first();
        
        $duration = floor(abs(strtotime($order->prescription->rx_end) - strtotime($order->prescription->next_supply_date)) / (60 * 60 * 24));

        $do = Order::select('do_number')->orderBy('do_number','DESC')->first();
        
        $do_number = $this->getDONumber($order->dispensing_by);

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
            'duration',
            'roles',
            'do_number',
            'orderItemSelected'
        ));
    }

    public function print_invoice()
    {
        return view('print.print2');
    }

    public function download_invoice1($id)
    {
        $date = Carbon::now()->format('d/m/Y');
        $batch = BatchOrder::where('order_id', $id)->first();
        $order_item = OrderItem::where('order_id', $id)->get();
        $pdf = PDF::loadView('print.print2', compact('batch', 'order_item', 'date'));
        return $pdf->stream('invoice_' .$batch->order->do_number. '.pdf');
    }

    public function download_invoice($id) {
        $order = Order::where('id', $id)->with(['batch'])->first();
        $pdf = PDF::loadView('print.print2', compact('order'));

        return $pdf->stream('Invoice_' . $order->batch->batch_no . '.pdf');
    }

    public function download_justify($id)
    {
        $order = DB::table('orders as a')
            ->join('patients as b', 'a.patient_id', 'b.id')
            ->join('cards as c', 'c.id', 'b.card_id')
            ->join('prescriptions as d', 'd.order_id', 'a.id')
            ->select('b.full_name as full_name', 'b.identification as identification', 'c.army_pension as army_pension', 'a.do_number as do_number', 'a.dispense_date as dispense_date', 'd.rx_number as rx_number')
            ->where('a.id', $id)->first();
        $pdf = PDF::loadView('print.printjustify', compact('order'));
        return $pdf->stream('justify_' .$order->do_number. '.pdf');
    }

    public function print_do()
    {
        return view('print.print3');
    }

    public function download_do($id)
    {
        $order = Order::where('id', $id)->first();
        $date = Carbon::now()->format('d/m/Y');
        $order_item = OrderItem::where('order_id', $id)->get();
        $delivery = Delivery::where('order_id', $id)->first();
        $prescription = Prescription::where('order_id', $id)->first();
        $pdf = PDF::loadView('print.print3', compact('order', 'delivery', 'prescription', 'order_item', 'date'));
        return $pdf->stream('do_' .$order->do_number. '.pdf');
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

    public function destroy($order_id)
    {
        $order = Order::findorfail($order_id);
        $order_items = OrderItem::where('order_id', $order->id);
        foreach ($order_items->get() as $oi) {
            $location = Location::where('item_id', $oi->myob_product_id)->first();
            if ($order->dispensing_method == 'Walkin') {
                $location->counter = $location->counter + $oi->quantity;
                $location->save();
            } elseif ($order->dispensing_method == 'Delivery') {
                $location->courier = $location->courier + $oi->quantity;
                $location->save();
            }

            $stock = new Stock();
            $stock->item_id = $oi->myob_product_id;
            $stock->quantity = $oi->quantity;
            $stock->balance = 0;
            $stock->source = 'return';
            $stock->source_id = $oi->id;
            $stock->source_date = Carbon::now()->format('Y-m-d');
            $stock->save();
        }

        Prescription::where('order_id', $order->id)->delete();
        Delivery::where('order_id', $order->id)->delete();
        $order_items->delete();
        $order->delete();

        return redirect()->action('OrderController@index')->with(['status' => true, 'message' => 'Successfully delete order']);
    }

    public function date_change($do_number) {
        $order = Order::where('do_number', $do_number)->whereNull('deleted_at')->first();
        return view('orders.date', compact('order'));
    }

    public function date_update($do_number, Request $request) {
        $order = Order::where('do_number', $do_number)->whereNull('deleted_at')->first();
        
        // log order date
        $log = new OrderDateLog();
        $log->order_id = $order->id;
        $log->do_number = $order->do_number;
        $log->issue_before = $order->created_at;
        $log->update_before = $order->updated_at;
        $log->dispense_before = $order->dispense_date;

        $order->created_at = $request->date_issue;
        $order->updated_at = $request->date_issue;
        $order->dispense_date = $request->date_dispense;
        $order->save();

        $order = Order::where('do_number', $do_number)->whereNull('deleted_at')->first();
        $log->issue_after = $order->created_at;
        $log->update_after = $order->updated_at;
        $log->dispense_after = $order->dispense_date;
        LogController::writeOrderDateLog($log);

        return view('orders.date', compact('order'));
    }

    public function calculateTotalAmount($order_id) {
        $order = Order::where('id', $order_id)->first();
        $total_amount = 0;

        foreach ($order->orderitem as $orderitem) {
            if ($orderitem->deleted_at == NULL) {
                $total_amount += $orderitem->price;
            }
        }

        $order->total_amount = $total_amount;
        $order->save();
    }
}
