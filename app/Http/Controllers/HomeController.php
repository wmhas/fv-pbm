<?php

namespace App\Http\Controllers;

use App\Models\Card;
use App\Models\Patient;
use App\Models\Order;
use App\Models\Prescription;
use App\Models\Status;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;


//test
class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        $orders = Order::all();
        $today = Carbon::now()->format('Y-m-d');

        $refills = DB::table('prescriptions as A')
            ->select('C.*', 'B.*', 'A.next_supply_date', 'A.order_id as order_id_submit')
            ->whereRaw('DATEDIFF(next_supply_date,?) >= 0', [$today])
            ->whereRaw('DATEDIFF(next_supply_date,?) <= 7', [$today])
            ->where('B.rx_interval', '>', '1')
            ->where('B.total_amount', '!=', '0')
            ->whereIn('B.status_id', [4, 5])
            ->whereNull('B.deleted_at')
            ->orderby('B.rx_interval', 'asc')
            ->join('orders as B', 'B.id', 'A.order_id')
            ->join('patients as C', 'C.id', 'B.patient_id')->skip(0)->take(4)
            ->get();

        $rx_expireds1 = Prescription::whereDate('rx_end', $today)->get();
        $rx_expireds = [];
        foreach ($rx_expireds1 as $rx) {
            if ($rx->order != NULL) {
                array_push($rx_expireds, $rx);
            }
        }
        $price_diff = DB::table('items as a')
            ->join('order_items as b', 'a.id', 'b.myob_product_id')
            ->join('orders as c', 'b.order_id', 'c.id')
            ->whereNull('b.deleted_at')
            ->whereNull('c.deleted_at')
            ->whereRaw('(ROUND((b.price/b.quantity), 2) - b.selling_price) != 0')
            ->select(DB::raw('count(*) as total'))
            ->first();

        $duplicate_do_total = DB::select(DB::raw('SELECT COUNT(*) AS total
                    FROM (
                        SELECT 
                            do_number, 
                            COUNT(do_number) as count 
                        FROM fvkl.orders 
                        WHERE deleted_at IS NULL 
                            AND return_timestamp IS NULL 
                            AND do_number != "" 
                        GROUP BY do_number 
                        HAVING COUNT(do_number) > 1
                    ) AS foo'))[0];

        $duplicate_do = DB::select(DB::raw('SELECT 
                            do_number
                        FROM fvkl.orders 
                        WHERE deleted_at IS NULL 
                            AND return_timestamp IS NULL 
                            AND do_number != "" 
                        GROUP BY do_number 
                        HAVING COUNT(do_number) > 1'));

        $roles = DB::table('model_has_roles')->join('users', 'model_has_roles.model_id', '=', 'users.id')->where("users.id", auth()->id())->first();
        if ($roles->role_id == 1) {
            return view('hq.home', compact('orders', 'refills', 'rx_expireds', 'roles', 'price_diff', 'duplicate_do_total', 'duplicate_do'));
        } elseif ($roles->role_id == 2) {
            return view('pharmacist.home', compact('orders', 'refills', 'rx_expireds', 'roles', 'price_diff', 'duplicate_do_total', 'duplicate_do'));
        } else {
            return view('home', compact('orders', 'refills', 'rx_expireds', 'roles', 'price_diff', 'duplicate_do_total', 'duplicate_do'));
        }
    }

    public function search_patient(Request $request)
    {
        $keyword = $request->get('keyword');
        $method = $request->get('method');
        // $keyword = preg_replace("/[^a-zA-Z0-9 ]/", "", $keyword);
        $cards = null;
        $patients = Patient::query();
        switch ($method) {
            case "identification":
                $patients = $patients
                    ->where('identification', 'like', '%' . strtoupper($keyword) . '%')
                    ->orderBy('identification', 'asc');
                break;
            case "army_pension":
                $cards = Card::where('army_pension', 'like', '%' . strtoupper($keyword) . '%')
                    ->orderBy('army_pension', 'asc')
                    ->pluck('id');

                $patients = $patients
                    ->whereIn('card_id', $cards)
                    ->orderBy('id', 'asc');
                break;
        }

        $patients = $patients->whereNull('deleted_at');

        $patients = $patients
            ->with('card')
            ->limit(500)
            ->paginate(15);
        $roles = DB::table('model_has_roles')->join('users', 'model_has_roles.model_id', '=', 'users.id')->where("users.id", auth()->id())->first();
        return view('patients.index', compact('keyword', 'patients', 'cards', 'roles', 'method'));
    }

    public function search_order(Request $request)
    {
        $method = null;
        $status_id = null;
        $statuses = Status::all();
        $keyword = $request->get('keyword');
        $keyword = preg_replace("/[^a-zA-Z0-9 ]/", "", $keyword);

        $orders = Order::with('prescription')->with('patient')->with('delivery')->where('do_number', 'like', '%' . strtoupper($keyword) . '%')
            ->orderBy('status_id', 'asc')
            ->orderBy('created_at', 'desc')->limit(500)
            ->paginate(15);
        $roles = DB::table('model_has_roles')->join('users', 'model_has_roles.model_id', '=', 'users.id')->where("users.id", auth()->id())->first();
        return view('orders.index', compact('keyword', 'orders', 'method', 'status_id', 'statuses', 'roles'));
    }

    public function view_order(Request $request)
    {
        $method = null;
        $keyword = null;
        $yearSelect = null;
        $status_id = $request->get('status');
        $years = DB::select(DB::raw("SELECT year(created_at) as name FROM fvkl.orders group by year(created_at)"));
        $statuses = Status::all();
        $orders = Order::with('prescription')->with('patient')->with('delivery')->where('status_id', 'like', '%' . strtoupper($status_id) . '%')
            ->orderBy('created_at', 'desc')
            ->limit(500)
            ->paginate(15);
        $roles = DB::table('model_has_roles')->join('users', 'model_has_roles.model_id', '=', 'users.id')->where("users.id", auth()->id())->first();
        return view('orders.index', compact('orders', 'method', 'keyword', 'status_id', 'years', 'yearSelect', 'statuses', 'roles'));
    }

    public function see_more_refill(Request $request)
    {
        $startDate = $request->get('startDate', date('Y-m-d'));
        $endDate = $request->get('endDate', date('Y-m-d'));
        $searchType = $request->get('search_type');
        $keyword = $request->get('keyword');
        $orders = Order::query()
            ->whereHas('prescription', function ($prescription) use ($startDate, $endDate) {
                $prescription->where('next_supply_date', '>=', $startDate);
                $prescription->where('next_supply_date', '<=', $endDate);
            })->with(['prescription', 'patient'])
            ->where('rx_interval', '>', '1')
            ->where('total_amount', '!=', '0')
            ->whereIn('status_id', [4, 5])
            ->paginate(15);
        $roles = DB::table('model_has_roles')->join('users', 'model_has_roles.model_id', '=', 'users.id')->where("users.id", auth()->id())->first();
        return view('reports.report_refill', compact('orders', 'roles', 'startDate', 'endDate', 'searchType', 'keyword'));
    }

    public function see_more_end()
    {
        $status_id = null;
        $method = null;
        $keyword = null;
        $today = Carbon::now()->format('Y-m-d');
        $order_lists = DB::table('orders')
            ->select('orders.*', 'prescriptions.*', 'patients.*')
            ->join('prescriptions', 'orders.id', '=', 'prescriptions.order_id')
            ->join('patients', 'orders.patient_id', '=', 'patients.id')
            ->where('prescriptions.rx_end', '=', $today)
            ->orderBy('orders.created_at', 'desc')
            ->whereNull('orders.deleted_at')
            ->paginate(15);

        $statuses = Status::all();

        $roles = DB::table('model_has_roles')->join('users', 'model_has_roles.model_id', '=', 'users.id')->where("users.id", auth()->id())->first();
        return view('orders.index', compact('order_lists', 'statuses', 'roles', 'status_id', 'method', 'keyword'));
    }
}
