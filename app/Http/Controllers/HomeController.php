<?php

namespace App\Http\Controllers;

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
            ->select('C.*', 'B.*', 'A.next_supply_date')
            ->whereRaw('DATEDIFF(next_supply_date,?) >= 0', [$today])
            ->whereRaw('DATEDIFF(next_supply_date,?) <= 7', [$today])
            ->where('B.rx_interval', '>', '1')
            ->where('B.total_amount', '!=', '0')
            ->whereIn('B.status_id', [4, 5])
            ->whereNull('B.deleted_at')
            ->join('orders as B', 'B.id', 'A.order_id')
            ->join('patients as C', 'C.id', 'B.patient_id')->skip(0)->take(4)
            ->get();

        $rx_expireds = Prescription::whereDate('rx_end', $today)->get();

        $roles = DB::table('model_has_roles')->join('users', 'model_has_roles.model_id', '=', 'users.id')->where("users.id", auth()->id())->first();
        if ($roles->role_id == 1) {
            return view('hq.home', compact('orders', 'refills', 'rx_expireds', 'roles'));
        } elseif ($roles->role_id == 2) {
            return view('pharmacist.home', compact('orders', 'refills', 'rx_expireds', 'roles'));
        } else {
            return view('home', compact('orders', 'refills', 'rx_expireds', 'roles'));
        }
    }

    public function search_patient(Request $request)
    {
        $keyword = $request->get('keyword');
        // $keyword = preg_replace("/[^a-zA-Z0-9 ]/", "", $keyword);
        $cards = null;
        $patients = Patient::with('card')->where('identification', 'like', '%' . strtoupper($keyword) . '%')
            ->orderBy('identification', 'asc')->limit(500)
            ->paginate(15);
        $roles = DB::table('model_has_roles')->join('users', 'model_has_roles.model_id', '=', 'users.id')->where("users.id", auth()->id())->first();
        return view('patients.index', compact('keyword', 'patients', 'cards', 'roles'));
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
        $status_id = $request->get('status');
        $statuses = Status::all();
        $orders = Order::with('prescription')->with('patient')->with('delivery')->where('status_id', 'like', '%' . strtoupper($status_id) . '%')
            ->orderBy('created_at', 'desc')
            ->limit(500)
            ->paginate(15);
        $roles = DB::table('model_has_roles')->join('users', 'model_has_roles.model_id', '=', 'users.id')->where("users.id", auth()->id())->first();
        return view('orders.index', compact('orders', 'method', 'keyword', 'status_id', 'statuses', 'roles'));
    }

    public function see_more_refill()
    {
        $today = Carbon::now()->format('Y-m-d');
        $order_lists = DB::table('orders')
            ->select('orders.*', 'prescriptions.*', 'patients.*')
            ->join('prescriptions', 'orders.id', '=', 'prescriptions.order_id')
            ->join('patients', 'orders.patient_id', '=', 'patients.id')
            ->whereIn('status_id', [4, 5])
            ->where([
                ['prescriptions.next_supply_date', '=', $today],
                ['orders.rx_interval', '>', '1'],
                ['orders.total_amount', '!=', '0'],
            ])
            ->orderby('prescriptions.next_supply_date', 'asc')
            ->whereNull('orders.deleted_at')
            ->paginate(15);
        $roles = DB::table('model_has_roles')->join('users', 'model_has_roles.model_id', '=', 'users.id')->where("users.id", auth()->id())->first();
        return view('reports.report_refill', compact('order_lists', 'roles'));
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
