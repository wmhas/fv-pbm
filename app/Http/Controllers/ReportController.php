<?php

namespace App\Http\Controllers;

use App\Exports\ItemExport;
use App\Models\OrderItem;
use App\Models\Status;
use Illuminate\Http\Request;
use App\Models\Order;
use App\Models\Item;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Illuminate\Support\Facades\Session;
use PDF;
use Excel;
use App\Exports\TransactionsExport;
use App\Exports\TransactionsSalesExport;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Collection;
use Illuminate\Pagination\LengthAwarePaginator;

class ReportController extends Controller
{
    function __construct()
    {
        $this->middleware('permission:report-sales', ['only' => ['report_sales']]);
        $this->middleware('permission:report-refill', ['only' => ['report_refill']]);
        $this->middleware('permission:report-items', ['only' => ['report_item', 'item_summary', 'export_sales_item']]);
        $this->middleware('permission:report-stocks', ['only' => ['report_stocks']]);
    }

    public function report_sales()
    {
        // $months = ['Jan', 'Feb', 'Mar', 'April', 'May', 'Jun', 'July', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'];
        // $totalAll = Order::whereIn('status_id', [4, 5])->sum('total_amount');
        // $monthsNo = [1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12];
        // $no_orders = [];
        // foreach ($monthsNo as $no) {
        //     $itemSale = Order::whereMonth('created_at', '=', $no)
        //         ->whereIn('status_id', [4, 5])
        //         ->sum('total_amount');

        //     array_push($no_orders, (int)$itemSale);
        // }

        $orders = Order::with('patient.tariff')->whereIn('status_id', [4, 5])
            ->orderBy('created_at', 'DESC')
            ->paginate(10);
        $roles = DB::table('model_has_roles')->join('users', 'model_has_roles.model_id', '=', 'users.id')->where("users.id", auth()->id())->first();
        // return view('reports.report_sales', ['months' => $months, 'no_orders' => $no_orders, 'totalAll' => $totalAll, 'orders' => $orders, 'roles' => $roles]);
        return view('reports.report_sales', ['orders' => $orders, 'roles' => $roles]);
    }

    public function search_sales(Request $request)
    {
        if($request->filter == 2){
            return $this->export_report($request);
        }
        // ini_set('max_execution_time', 1000);
        if ($request->post('startDate') != null && $request->post('endDate') != null) {
            $orders= Order::with('patient.tariff')->whereIn('status_id', [4, 5])
            ->whereDate('created_at', '>=', $request->startDate)
            ->whereDate('created_at', '<=', $request->endDate)
            ->orderBy('created_at', 'DESC')
            ->paginate(10);
        }
        $roles = DB::table('model_has_roles')->join('users', 'model_has_roles.model_id', '=', 'users.id')->where("users.id", auth()->id())->first();
        return view('reports.report_sales', ['orders' => $orders, 'roles' => $roles]);
    }

    public function export_report($request)
    {   
        $startDate = false;
        $endDate = false;

        if ($request->post('startDate') != null && $request->post('endDate') != null) {
            $startDate = $request->startDate;
            $endDate = $request->endDate;
        }

        $transaction = new TransactionsSalesExport($startDate, $endDate);
        if ($transaction->collection()->count() > 0) {
            return Excel::download($transaction, 'transactionssales.xlsx');
        }

        $request->session()->flash('error', 'No Data to Export');
        return redirect(url('/report/report_sales'));
    }

    public function report_refill(Request $request)
    {
        // dd($request->all());
        $startDate = $request->startDate;
        $endDate = $request->endDate;

        $orders = Order::query()
            ->whereHas('prescription', function ($prescription) use ($startDate, $endDate) {
                if ($startDate) {
                    $prescription->where('next_supply_date', '>=', $startDate);
                }
                if ($endDate) {
                    $prescription->where('next_supply_date', '<=', $endDate);
                }
            })->with(['prescription', 'patient'])
            ->where('rx_interval', '>', '1')
            ->where('total_amount', '!=', '0')
            ->whereIn('status_id', [4, 5])
            ->paginate(15);

        $roles = DB::table('model_has_roles')->join('users', 'model_has_roles.model_id', '=', 'users.id')->where("users.id", auth()->id())->first();
        return view('reports.report_refill', compact('orders', 'roles'));
    }

    private function getItems ($startDate, $endDate, $method, $keyword, $order = 'item_code', $direction = 'asc')
    {
        $items = Item::query();

        if ($startDate && $endDate) {
            $items = $items->with(['order_items.order' => function ($order) use ($startDate, $endDate) {
                $order->whereBetween('dispense_date', [$startDate, $endDate]);
            }]);
        }

        if ($keyword) {
            switch ($method) {
                case ('ItemNumber'):
                    $items = $items->where('item_code', 'like', '%' . strtoupper($keyword) . '%');
                    break;

                case ('ItemName'):
                    $items = $items->where('brand_name', 'like', '%' . strtoupper($keyword) . '%');
                    break;
            }
        }

        return $items->orderBy($order, $direction);
    }

    public function report_item(Request $request)
    {
        $startDate = $request->get('start_date', date('Y-m-d'));
        $endDate = $request->get('end_date', date('Y-m-d'));
        $method = $request->get('method');
        $keyword = $request->get('keyword');
        $keyword = preg_replace("/[^a-zA-Z0-9 ]/", "", $keyword);
        if (!$method) { $keyword = null; }
        $order = $request->get('order', 'item_code');
        $direction = $request->get('direction', 'asc');

        $items = $this->getItems($startDate, $endDate, $method, $keyword, $order, $direction)->paginate(15);
        $roles = DB::table('model_has_roles')->join('users', 'model_has_roles.model_id', '=', 'users.id')->where("users.id", auth()->id())->first();
        return view('reports.report_item', compact('roles', 'items', 'keyword', 'method', 'startDate', 'endDate', 'order', 'direction'));
    }

    public function report_item_export (Request $request) {
        $startDate = $request->get('start_date', date('Y-m-d'));
        $endDate = $request->get('end_date', date('Y-m-d'));
        $method = $request->get('method');
        $keyword = $request->get('keyword');
        $keyword = preg_replace("/[^a-zA-Z0-9 ]/", "", $keyword);
        $order = $request->get('order', 'item_code');
        $direction = $request->get('direction', 'asc');

        $items = $this->getItems($startDate, $endDate, $method, $keyword, $order, $direction)->get();
        $export = new ItemExport($items);
        return Excel::download($export, 'items.xlsx');
    }

    public function item_summary(Request $request, $item_id)
    {
        $item = Item::where('id', $item_id)->first();
        $start_date = $request->get('startDate', null);
        $end_date = $request->get('endDate', null);
        if ($request->get('startDate') != null && $request->get('endDate') != null) {
            // $patient_lists = DB::table('orders as a')
            // ->join('patients as b', 'b.id', '=', 'a.patient_id')
            // ->join('order_items as c', 'c.order_id', '=', 'a.id')
            // ->join('items as d', 'd.id', '=', 'c.myob_product_id')
            // ->selectRaw('b.full_name, SUM(c.quantity) as quantity, SUM(c.price) as amount')
            // ->where('a.status_id', 4)
            // ->whereDate('a.created_at', '>=', $request->get('startDate'))
            // ->whereDate('a.created_at', '<=', $request->get('endDate'))
            // // ->orderby('b.full_name', 'asc')
            // ->groupby('b.full_name')
            // ->paginate(15);
            $patient_lists = DB::table('orders as a')
            ->join('order_items as b', 'b.order_id', '=', 'a.id')
            ->join('patients as c', 'c.id', '=', 'a.patient_id')
            ->selectRaw('c.id, c.full_name, SUM(b.quantity) as quantity, SUM(b.price) as amount')
            ->where('b.myob_product_id', $item->id)
            ->whereDate('a.created_at', '>=', $request->startDate)
            ->whereDate('a.created_at', '<=', $request->endDate)
            ->whereIn('a.status_id', [4,5])
            ->whereNull('a.deleted_at')
            ->whereNull('a.return_timestamp')
            ->whereNull('b.deleted_at')
            ->groupby('c.id')
            ->paginate(15);
        }
        $roles = DB::table('model_has_roles')->join('users', 'model_has_roles.model_id', '=', 'users.id')->where("users.id", auth()->id())->first();
        return view('reports.item_summary', compact('roles', 'patient_lists', 'start_date', 'end_date', 'item'));
    }

    public function export_sales_item(Request $request)
    {
        // ini_set('max_execution_time', 1000);
        if ($request->post('startDate') != null && $request->post('endDate') != null) {
            $patient_lists = DB::table('orders as a')
            ->join('order_items as b', 'b.order_id', '=', 'a.id')
            ->join('patients as c', 'c.id', '=', 'a.patient_id')
            ->selectRaw('c.id, c.full_name, SUM(b.quantity) as quantity, SUM(b.price) as amount')
            ->where('b.myob_product_id', $request->item_id)
            ->whereDate('a.created_at', '>=', $request->startDate)
            ->whereDate('a.created_at', '<=', $request->endDate)
            ->whereIn('a.status_id', [4,5])
            ->whereNull('a.deleted_at')
            ->whereNull('a.return_timestamp')
            ->whereNull('b.deleted_at')
            ->groupby('c.id')
            ->get();
        }
        // dd($patient_lists);
        $roles = DB::table('model_has_roles')->join('users', 'model_has_roles.model_id', '=', 'users.id')->where("users.id", auth()->id())->first();
        $pdf = PDF::loadView('reports.exportsalesitem', compact('patient_lists', 'roles'));
        return $pdf->stream('patient_lists.pdf');
    }

    public function report_stocks()
    {
        // $items = DB::table('v_sum_order_items as a')
        // ->join('locations as b', 'b.item_id', 'a.myob_product_id')
        // ->join('myob_products as c', 'c.ItemID', 'b.item_id')
        // ->join('myob_product_details as d', 'd.myob_product_id', 'c.ItemNumber')
        // ->select('c.ItemNumber','c.ItemName', 'b.courier as on_hand' , 'a.sales_quantity as committed',
        // 'b.courier AS available')->get();
        $items = DB::table('items as a')
            ->join('locations as b', 'b.item_id', 'a.id')
            // ->join('myob_product_details as c', 'c.myob_product_id', 'a.ItemNumber')
            // ->join('v_sum_order_items as d', 'd.myob_product_id', 'b.item_id')
            ->select('a.id', 'a.brand_name', 'a.item_code', 'b.courier as on_hand')
            ->get();

        $sales = DB::table('v_sum_order_items')
            ->select('myob_product_id', 'sales_quantity as committed')
            ->get()->toArray();
        // dd($sales);
        foreach ($sales as $sale) {
            foreach ($items as $item) {
                if ($sale->myob_product_id == $item->id) {
                    $item->committed = $sale->committed;
                    // $item->available = $item->on_hand - $sale->committed;
                }
            }
        }
        // dd($items);
        $date = Carbon::now()->format('d/m/Y');
        $roles = DB::table('model_has_roles')->join('users', 'model_has_roles.model_id', '=', 'users.id')->where("users.id", auth()->id())->first();
        $pdf = PDF::loadView('reports.report_stocks', compact('items', 'roles', 'date'));
        return $pdf->stream('patient_lists.pdf');
    }

    public function sales_report()
    {
        $months = ['Jan', 'Feb', 'Mar', 'April', 'May', 'Jun', 'July', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'];
        $totalAll = Order::whereIn('status_id', [4, 5])->sum('total_amount');
        $monthsNo = [1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12];
        $no_orders = [];
        foreach ($monthsNo as $no) {
            $itemSale = Order::whereMonth('created_at', '=', $no)
                ->whereIn('status_id', [4, 5])
                ->sum('total_amount');

            array_push($no_orders, (int)$itemSale);
        }

        $orders = Order::with('patient')->whereIn('status_id', [4, 5])
            ->orderBy('created_at', 'DESC')
            ->paginate(10);
        $ord = Order::getorder(null,null);
        $order = $this->paginate($ord);
        $roles = DB::table('model_has_roles')->join('users', 'model_has_roles.model_id', '=', 'users.id')->where("users.id", auth()->id())->first();
        return view('reports.sales_report', ['months' => $months, 'no_orders' => $no_orders, 'totalAll' => $totalAll, 'orders' => $orders, 'roles' => $roles, 'order'=>$order]);
    }

    public function search_report_sales($request)
    {
        $months = ['Jan', 'Feb', 'Mar', 'April', 'May', 'Jun', 'July', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'];
        $totalAll = Order::whereIn('status_id', [4, 5])->sum('total_amount');
        $monthsNo = [1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12];
        $no_orders = [];
        foreach ($monthsNo as $no) {
            $itemSale = Order::whereMonth('created_at', '=', $no)
                ->whereIn('status_id', [4, 5])
                ->sum('total_amount');

            array_push($no_orders, (int)$itemSale);
        }

        $orders = Order::with('patient')->whereIn('status_id', [4, 5])
            ->orderBy('created_at', 'DESC')
            ->paginate(10);
        $ord = Order::getorder($request->startDate,$request->endDate);
        $order = $this->paginate($ord);
        $roles = DB::table('model_has_roles')->join('users', 'model_has_roles.model_id', '=', 'users.id')->where("users.id", auth()->id())->first();
        return view('reports.sales_report', ['months' => $months, 'no_orders' => $no_orders, 'totalAll' => $totalAll, 'orders' => $orders, 'roles' => $roles, 'order'=>$order]);
    }

    public function paginate($items, $perPage = 10, $page = null, $options = [])
    {
        $page = $page ?: (Paginator::resolveCurrentPage() ?: 1);
        $items = $items instanceof Collection ? $items : Collection::make($items);
        return new LengthAwarePaginator($items->forPage($page, $perPage), $items->count(), $perPage, $page, $options);
    }

    public function export_sales_item_excel(Request $request)
    {
        if($request->filter == 1){
            return $this->search_report_sales($request);
        }
        
        $startDate = false;
        $endDate = false;

        if ($request->post('startDate') != null && $request->post('endDate') != null) {
            $startDate = $request->startDate;
            $endDate = $request->endDate;
        }

        $transaction = new TransactionsExport($startDate, $endDate);
        if ($transaction->collection()->count() > 0) {
            return Excel::download($transaction, 'transactions.xlsx');
        }

        $request->session()->flash('error', 'No Data to Export');
        return redirect(url('/report/sales_report'));
    }

}
