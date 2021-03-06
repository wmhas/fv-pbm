<?php

namespace App\Http\Controllers;

use App\Download;
use App\Exports\ItemExport;
use App\Exports\ItemSummaryExport;
use App\Exports\ReportRefillExport;
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
use App\Exports\StockReportExport;
use App\Jobs\ExportTransactionJob;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Collection;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Storage;

class ReportController extends Controller
{
    function __construct()
    {
        $this->middleware('permission:report-sales', ['only' => ['report_sales']]);
        $this->middleware('permission:report-refill', ['only' => ['report_refill']]);
        $this->middleware('permission:report-items', ['only' => ['report_item', 'item_summary', 'export_sales_item']]);
        $this->middleware('permission:report-stocks', ['only' => ['report_stocks']]);
    }

    public function report_sales(Request $request)
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

        if ($request->startDate != null && $request->endDate != null) {
            $startDate = $request->startDate;
            $endDate = $request->endDate;
        } else {
            $startDate = date('Y-m-d');
            $endDate = date('Y-m-d');
        }

        if ($request->page){
            $page = $request->page;
        } else {
            $page = 1;
        }

        // $orders = Order::join("patients","patients.id","=","orders.patient_id")
            // ->leftjoin("tariffs","tariffs.id","=","patients.tariff_id")
            // ->join("cards","cards.id","=","patients.card_id")
            // ->join("prescriptions","prescriptions.order_id","=","orders.id")
            // ->join("order_items","order_items.order_id","=","orders.id")
            // ->join("items","items.id","=","order_items.myob_product_id")
            // ->join("states","states.id","=","patients.state_id")
            // ->whereIn('orders.status_id', [4, 5])
            // ->whereDate('orders.created_at', '>=', $startDate)
            // ->whereDate('orders.created_at', '<=', $endDate)
            // ->orderBy('orders.created_at', 'DESC')
            // ->paginate(10, ['*'], 'page', $page);

        $orders = Order::whereIn('orders.status_id', [3, 4, 5])
            ->whereDate('orders.dispense_date', '>=', $startDate)
            ->whereDate('orders.dispense_date', '<=', $endDate)
            ->orderBy('orders.dispense_date', 'DESC')
            ->orderBy('orders.id', 'DESC')
            ->paginate(10, ['*'], 'page', $page);

        $roles = DB::table('model_has_roles')->join('users', 'model_has_roles.model_id', '=', 'users.id')->where("users.id", auth()->id())->first();
        // return view('reports.report_sales', ['months' => $months, 'no_orders' => $no_orders, 'totalAll' => $totalAll, 'orders' => $orders, 'roles' => $roles]);
        return view('reports.report_sales', ['orders' => $orders, 'roles' => $roles,'startDate'=>$startDate,'endDate'=>$endDate,'page'=>$page]);
    }

    public function search_sales(Request $request)
    {
        if($request->filter == 2){
            return $this->export_report($request);
        }

        $page = $request->page;

        // ini_set('max_execution_time', 1000);
        if ($request->startDate != null && $request->endDate != null) {
            // $orders= Order::join("patients","patients.id","=","orders.patient_id")
            // ->leftjoin("tariffs","tariffs.id","=","patients.tariff_id")
            // ->join("cards","cards.id","=","patients.card_id")
            // ->join("prescriptions","prescriptions.order_id","=","orders.id")
            // ->join("order_items","order_items.order_id","=","orders.id")
            // ->join("items","items.id","=","order_items.myob_product_id")
            // ->join("states","states.id","=","patients.state_id")
            // ->whereIn('orders.status_id', [4, 5])
            // ->whereDate('orders.created_at', '>=', $request->startDate)
            // ->whereDate('orders.created_at', '<=', $request->endDate)
            // ->orderBy('orders.created_at', 'DESC')
            // ->paginate(10, ['*'], 'page', $page);

            $orders = Order::whereIn('orders.status_id', [3, 4, 5])
                ->whereDate('orders.dispense_date', '>=', $request->startDate)
                ->whereDate('orders.dispense_date', '<=', $request->endDate)
                ->orderBy('orders.dispense_date', 'DESC')
                ->orderBy('orders.id', 'DESC')
                ->paginate(10, ['*'], 'page', $page);
        }
        $roles = DB::table('model_has_roles')->join('users', 'model_has_roles.model_id', '=', 'users.id')->where("users.id", auth()->id())->first();
        // return view('reports.report_sales', ['orders' => $orders, 'roles' => $roles,'startDate'=>$request->startDate,'endDate'=>$request->endDate,'page'=>$page]);
        return view('reports.report_sales', ['orders' => $orders, 'roles' => $roles,'startDate'=>$request->startDate,'endDate'=>$request->endDate,'page'=>$page]);
    }

    public function export_report(Request $request)
    {   
        $startDate = false;
        $endDate = false;

        if ($request->startDate != null && $request->endDate != null) {
            $startDate = $request->startDate;
            $endDate = $request->endDate;
        }

        $transaction = new TransactionsSalesExport($startDate, $endDate);
        if ($transaction->collection()->count() > 0) {
            return Excel::download($transaction, 'Sales Report Summary ('. $startDate . " to " . $endDate .').xlsx');
        }

        $request->session()->flash('error', 'No Data to Export');
        return redirect(url('/report/report_sales'));
    }

    public function report_refill(Request $request)
    {
        // dd($request->all());
        $startDate = $request->get('startDate', date('Y-m-d'));
        $endDate = $request->get('endDate', date('Y-m-d'));
        $searchType = $request->get('search_type');
        $keyword = $request->get('keyword');
        $export = $request->get('export', false);

        $orders = Order::query()
            ->whereHas('prescription', function ($prescription) use ($startDate, $endDate) {
                if ($startDate) {
                    $prescription->where('next_supply_date', '>=', $startDate);
                }
                if ($endDate) {
                    $prescription->where('next_supply_date', '<=', $endDate);
                }
            })
            ->whereHas('patient', function ($patient) use ($keyword, $searchType) {
                if ($keyword && $searchType === 'patient_name') {
                    $patient->where('full_name', 'like', '%' . $keyword . '%');
                }
            })
            ->with(['prescription', 'patient'])
            ->where('rx_interval', '>', '1')
            ->where('total_amount', '!=', '0')
            ->whereIn('status_id', [3, 4, 5]);

        if ($searchType === 'do_no' && $keyword) {
            $orders->where('do_number', 'like', '%' . $keyword . '%');
        }

        if ($export) {
            $refill = new ReportRefillExport($orders->get(), $startDate, $endDate);
            if ($refill->collection()->count() > 0) {
                return \Maatwebsite\Excel\Facades\Excel::download($refill, 'Refill Report (' . $startDate . ' to ' . $endDate . ').xlsx');
            }
            $request->session()->flash('error', 'No Data to Export');
        }

        $orders = $orders->paginate(15);

        $roles = DB::table('model_has_roles')->join('users', 'model_has_roles.model_id', '=', 'users.id')->where("users.id", auth()->id())->first();
        return view('reports.report_refill', compact('orders', 'roles', 'startDate', 'endDate', 'keyword', 'searchType', 'export'));
    }

    private function getItems ($startDate, $endDate, $method, $keyword, $order = 'item_code', $direction = 'asc')
    {
        $items = Item::query();

        if ($startDate && $endDate) {
            $items = $items->with(['order_items.order' => function ($order) use ($startDate, $endDate) {
                $order->whereBetween('dispense_date', [$startDate, $endDate])->whereIn('orders.status_id', [3, 4, 5]);
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
            ->selectRaw('a.id, a.dispense_date, a.do_number, a.dispensing_method, c.id as patient, c.full_name, SUM(b.quantity) as quantity, SUM(b.price) as amount')
            ->where('b.myob_product_id', $item->id)
            ->whereDate('a.dispense_date', '>=', $request->startDate)
            ->whereDate('a.dispense_date', '<=', $request->endDate)
            ->whereIn('a.status_id', [3,4,5])
            ->whereNull('a.deleted_at')
            ->whereNull('a.return_timestamp')
            ->whereNull('b.deleted_at')
            ->orderBy('a.dispense_date', 'DESC')
            ->groupby('a.id')
            ->paginate(15);
        }
        $roles = DB::table('model_has_roles')->join('users', 'model_has_roles.model_id', '=', 'users.id')->where("users.id", auth()->id())->first();
        return view('reports.item_summary', compact('roles', 'patient_lists', 'start_date', 'end_date', 'item'));
    }

    public function export_sales_item(Request $request)
    {
        // if ($request->post('startDate') != null && $request->post('endDate') != null) {
        //     $patient_lists = DB::table('orders as a')
        //     ->join('order_items as b', 'b.order_id', '=', 'a.id')
        //     ->join('patients as c', 'c.id', '=', 'a.patient_id')
        //     ->selectRaw('a.dispense_date, a.do_number, a.dispensing_method, c.full_name, SUM(b.quantity) as quantity, SUM(b.price) as amount')
        //     ->where('b.myob_product_id', $request->item_id)
        //     ->whereDate('a.dispense_date', '>=', $request->startDate)
        //     ->whereDate('a.dispense_date', '<=', $request->endDate)
        //     ->whereIn('a.status_id', [3,4,5])
        //     ->whereNull('a.deleted_at')
        //     ->whereNull('a.return_timestamp')
        //     ->whereNull('b.deleted_at')
        //     ->orderBy('a.dispense_date', 'DESC')
        //     ->groupby('a.id')
        //     ->get();
        // }

        // $roles = DB::table('model_has_roles')->join('users', 'model_has_roles.model_id', '=', 'users.id')->where("users.id", auth()->id())->first();
        // $pdf = PDF::loadView('reports.exportsalesitem', compact('patient_lists', 'roles'));
        // return $pdf->stream('Item Summary ( ' . $request->startDate . ' to ' . $request->endDate . ' ).pdf');

        $dateStart = $request->startDate;
        $dateEnd = $request->endDate;
        $itemId = $request->item_id;
        $itemName = Item::where('id', $itemId)->first()->brand_name;

        return Excel::download(new ItemSummaryExport($dateStart, $dateEnd, $itemId, $itemName), 'Item Summary ( ' . $dateStart . ' to ' . $dateEnd . ' ).xlsx');
    }

    public function report_stock_pdf()
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
        // $months = ['Jan', 'Feb', 'Mar', 'April', 'May', 'Jun', 'July', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'];
        // $totalAll = Order::whereIn('status_id', [4, 5])->sum('total_amount');
        // $monthsNo = [1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12];
        // $no_orders = [];
        // foreach ($monthsNo as $no) {
        //     $itemSale = Order::whereMonth('updated_at', '=', $no)
        //         ->whereIn('status_id', [4, 5])
        //         ->sum('total_amount');

        //     array_push($no_orders, (int)$itemSale);
        // }

        // $startDate = date('Y-m-d');
        // $endDate = date('Y-m-d');
        // $orders = Order::getorder(null,null,1);
        // $order = $orders["collectOrder"];
        // $links = $orders["links"];
        // $roles = DB::table('model_has_roles')->join('users', 'model_has_roles.model_id', '=', 'users.id')->where("users.id", auth()->id())->first();
        // return view('reports.sales_report', ['months' => $months, 'no_orders' => $no_orders, 'totalAll' => $totalAll, 'order' => $order, 'roles' => $roles, 'order'=>$order,'startDate'=>$startDate,'endDate'=>$endDate,'page'=>1,'links'=>$links]);
            
        $page = 1;

        $startDate = $endDate = date('Y-m-d');

        if ($startDate != null && $endDate != null) {
            
            $orders = Order::whereIn('orders.status_id', [3, 4, 5])
                ->whereDate('orders.dispense_date', '>=', $startDate)
                ->whereDate('orders.dispense_date', '<=', $endDate)
                ->orderBy('orders.dispense_date', 'DESC')
                ->paginate(10, ['*'], 'page', $page);
        }

        $links = $orders->links();

        $roles = DB::table('model_has_roles')->join('users', 'model_has_roles.model_id', '=', 'users.id')->where("users.id", auth()->id())->first();

        return view('reports.sales_report', ['orders' => $orders, 'links'=> $links, 'roles' => $roles,'startDate'=>$startDate,'endDate'=>$endDate,'page'=>$page]);
    }

    public function search_report_sales($request)
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

        // $startDate = $request->startDate;
        // $endDate = $request->endDate;
        // $page = $request->page;

        // $orders = Order::getorder($startDate, $endDate, $page);
        // $order = $orders["collectOrder"];
        // $links = $orders["links"];
        // $roles = DB::table('model_has_roles')->join('users', 'model_has_roles.model_id', '=', 'users.id')->where("users.id", auth()->id())->first();
        // return view('reports.sales_report', ['months' => $months, 'no_orders' => $no_orders, 'totalAll' => $totalAll, 'roles' => $roles, 'order'=>$order,'startDate'=>$startDate,'endDate'=>$endDate,'page'=>$page,'links'=>$links]);

        $page = $request->page;

        if ($request->startDate != null && $request->endDate != null) {
            $orders = Order::whereIn('orders.status_id', [3, 4, 5])
                ->whereDate('orders.dispense_date', '>=', $request->startDate)
                ->whereDate('orders.dispense_date', '<=', $request->endDate)
                ->orderBy('orders.dispense_date', 'DESC')
                ->paginate(10, ['*'], 'page', $page);
        }

        $links = $orders->links();

        $roles = DB::table('model_has_roles')->join('users', 'model_has_roles.model_id', '=', 'users.id')->where("users.id", auth()->id())->first();

        return view('reports.sales_report', ['orders' => $orders, 'links'=> $links, 'roles' => $roles,'startDate'=>$request->startDate,'endDate'=>$request->endDate,'page'=>$page]);
    }

    public function export_sales_item_excel(Request $request)
    {
        ini_set('max_execution_time', 0);
        if($request->filter == 1){
            return $this->search_report_sales($request);
        }
        
        $startDate = false;
        $endDate = false;
        if ($request->startDate != null && $request->endDate != null) {
            $startDate = $request->startDate;
            $endDate = $request->endDate;
        }

        $transaction = new TransactionsExport($startDate, $endDate);
        if ($transaction->collection()->count() > 0) {
            return Excel::download($transaction, 'Sales Report Details ('. $startDate . " to " . $endDate .').xlsx');
        }

        $request->session()->flash('error', 'No Data to Export');
        return redirect(url('/report/sales_report'));
    }

    public function report_stocks(Request $request)
    {

        if ($request->page){
            $page = $request->page;
        } else {
            $page = 1;
        }

        $startTime = " 00:00:00";
        $endTime = " 23:59:59";

        $startDate = $endDate = date('Y-m-d');

        $items = DB::table('items as a')
            ->join('locations as b', 'b.item_id', 'a.id');
            
        // if ($startDate != null && $endDate != null) {
        //     $items = $items->whereDate('oi.created_at', '>=', $startDate)
        //         ->whereDate('oi.created_at', '<=', $endDate);
        // }

        $items = $items->select(
            'a.id', 
            'a.brand_name', 
            'a.item_code', 
            'b.courier as on_hand',
            'b.staff',
            'b.store',
            'b.counter',
            'b.courier',
            'b.store'
        )
        ->distinct('a.brand_name')
        ->paginate(10, ['*'], 'page', $page);

        $links = $items->links();

        $committed_courier = [];
        $committed_counter = [];

        foreach($items as $key => $val){
            $db_courier = DB::select(DB::raw("SELECT SUM(order_items.quantity) as com_courier FROM orders JOIN order_items ON orders.id = order_items.order_id WHERE orders.dispensing_method = 'Delivery' AND order_items.myob_product_id = ".$val->id." AND orders.dispense_date >= '".$startDate.$startTime."' AND orders.dispense_date <= '".$endDate.$endTime."' AND orders.status_id IN (3, 4, 5) AND orders.return_timestamp IS NULL AND orders.deleted_at IS NULL AND order_items.deleted_at IS NULL;"));
            if ($db_courier[0]->com_courier) {
                $committed_courier[$key] = $db_courier[0]->com_courier;
            } else {
                $committed_courier[$key] = 0;
            }

            $db_counter = DB::select(DB::raw("SELECT SUM(order_items.quantity) as com_counter FROM orders JOIN order_items ON orders.id = order_items.order_id WHERE orders.dispensing_method = 'Walkin' AND order_items.myob_product_id = ".$val->id." AND orders.dispense_date >= '".$startDate.$startTime."' AND orders.dispense_date <= '".$endDate.$endTime."' AND orders.status_id IN (3, 4, 5) AND orders.return_timestamp IS NULL AND orders.deleted_at IS NULL AND order_items.deleted_at IS NULL;"));
            if ($db_counter[0]->com_counter) {
                $committed_counter[$key] = $db_counter[0]->com_counter;
            } else {
                $committed_counter[$key] = 0;
            }

        }  

        // $sales = DB::table('v_sum_order_items')
        //     ->select('myob_product_id', 'sales_quantity as committed')
        //     ->get()->toArray();
        
        // foreach ($sales as $sale) {
        //     foreach ($items as $item) {
        //         if ($sale->myob_product_id == $item->id) {
        //             $item->committed = $sale->committed;
        //             // $item->available = $item->on_hand - $sale->committed;
        //         }
        //     }
        // }

        $roles = DB::table('model_has_roles')->join('users', 'model_has_roles.model_id', '=', 'users.id')->where("users.id", auth()->id())->first();
        return view('reports.report_stocks', ['items' => $items, 'roles'=> $roles,'startDate'=>$startDate, 'endDate'=>$endDate, 'links'=>$links, 'page'=>$page, 'committed_courier'=> $committed_courier, 'committed_counter'=> $committed_counter]);
    }

    public function search_report_stock($request)
    {

        $page = $request->page;
        $startDate = $request->startDate; 
        $endDate = $request->endDate;

        $startTime = " 00:00:00";
        $endTime = " 23:59:59";

        $items = DB::table('items as a')
            ->join('locations as b', 'b.item_id', 'a.id');
            
        // if ($startDate != null && $endDate != null) {
        //     $items = $items->whereDate('oi.created_at', '>=', $startDate)
        //         ->whereDate('oi.created_at', '<=', $endDate);
        // }

        $items = $items->select(
            'a.id', 
            'a.brand_name', 
            'a.item_code', 
            'b.courier as on_hand',
            'b.staff',
            'b.store',
            'b.counter',
            'b.courier',
            'b.store'
        )
        ->distinct('a.brand_name')
        ->paginate(10, ['*'], 'page', $page);

        $links = $items->links();

        $committed_courier = [];
        $committed_counter = [];

        foreach($items as $key => $val){
            $db_courier = DB::select(DB::raw("SELECT SUM(order_items.quantity) as com_courier FROM orders JOIN order_items ON orders.id = order_items.order_id WHERE orders.dispensing_method = 'Delivery' AND order_items.myob_product_id = ".$val->id." AND orders.dispense_date >= '".$startDate.$startTime."' AND orders.dispense_date <= '".$endDate.$endTime."' AND orders.status_id IN (3, 4, 5) AND orders.return_timestamp IS NULL AND orders.deleted_at IS NULL AND order_items.deleted_at IS NULL;"));
            if ($db_courier[0]->com_courier) {
                $committed_courier[$key] = $db_courier[0]->com_courier;
            } else {
                $committed_courier[$key] = 0;
            }

            $db_counter = DB::select(DB::raw("SELECT SUM(order_items.quantity) as com_counter FROM orders JOIN order_items ON orders.id = order_items.order_id WHERE orders.dispensing_method = 'Walkin' AND order_items.myob_product_id = ".$val->id." AND orders.dispense_date >= '".$startDate.$startTime."' AND orders.dispense_date <= '".$endDate.$endTime."' AND orders.status_id IN (3, 4, 5) AND orders.return_timestamp IS NULL AND orders.deleted_at IS NULL AND order_items.deleted_at IS NULL;"));
            if ($db_counter[0]->com_counter) {
                $committed_counter[$key] = $db_counter[0]->com_counter;
            } else {
                $committed_counter[$key] = 0;
            }

        }

        // $sales = DB::table('v_sum_order_items')
        //     ->select('myob_product_id', 'sales_quantity as committed')
        //     ->get()->toArray();
        
        // foreach ($sales as $sale) {
        //     foreach ($items as $item) {
        //         if ($sale->myob_product_id == $item->id) {
        //             $item->committed = $sale->committed;
        //             // $item->available = $item->on_hand - $sale->committed;
        //         }
        //     }
        // }

        $roles = DB::table('model_has_roles')->join('users', 'model_has_roles.model_id', '=', 'users.id')->where("users.id", auth()->id())->first();
        return view('reports.report_stocks', ['items' => $items, 'roles'=> $roles,'startDate'=>$startDate, 'endDate'=>$endDate, 'links'=>$links, 'page'=>$page, 'committed_courier'=> $committed_courier, 'committed_counter'=> $committed_counter]);
    }

    public function export_stock_item(Request $request)
    {
        ini_set("max_execution_time", 1000000000);
        if ($request->filter == 1) {
            return $this->search_report_stock($request);
        }
        
        $page = $request->page;
        $startDate = $request->startDate; 
        $endDate = $request->endDate;

        $startTime = " 00:00:00";
        $endTime = " 23:59:59";

        $committed_courier = [];
        $committed_counter = [];

        $roles = DB::table('model_has_roles')->join('users', 'model_has_roles.model_id', '=', 'users.id')->where("users.id", auth()->id())->first();

        if ($request->filter == 3) {

            $items = DB::table('items as a')
            ->join('locations as b', 'b.item_id', 'a.id');
            
            // if ($startDate != null && $endDate != null) {
            //     $items = $items->whereDate('oi.created_at', '>=', $startDate)
            //         ->whereDate('oi.created_at', '<=', $endDate);
            // }

            $items = $items->select(
                'a.id', 
                'a.brand_name', 
                'a.item_code', 
                'b.courier as on_hand',
                'b.staff',
                'b.store',
                'b.counter',
                'b.courier',
                'b.store'
            )
            ->get();

            foreach($items as $key => $val){
                $db_courier = DB::select(DB::raw("SELECT SUM(order_items.quantity) as com_courier FROM orders JOIN order_items ON orders.id = order_items.order_id WHERE orders.dispensing_method = 'Delivery' AND order_items.myob_product_id = ".$val->id." AND orders.dispense_date >= '".$startDate.$startTime."' AND orders.dispense_date <= '".$endDate.$endTime."' AND orders.status_id IN (3, 4, 5) AND orders.return_timestamp IS NULL AND orders.deleted_at IS NULL AND order_items.deleted_at IS NULL;"));
                if ($db_courier[0]->com_courier) {
                    $committed_courier[$key] = $db_courier[0]->com_courier;
                } else {
                    $committed_courier[$key] = 0;
                }

                $db_counter = DB::select(DB::raw("SELECT SUM(order_items.quantity) as com_counter FROM orders JOIN order_items ON orders.id = order_items.order_id WHERE orders.dispensing_method = 'Walkin' AND order_items.myob_product_id = ".$val->id." AND orders.dispense_date >= '".$startDate.$startTime."' AND orders.dispense_date <= '".$endDate.$endTime."' AND orders.status_id IN (3, 4, 5) AND orders.return_timestamp IS NULL AND orders.deleted_at IS NULL AND order_items.deleted_at IS NULL;"));
                if ($db_counter[0]->com_counter) {
                    $committed_counter[$key] = $db_counter[0]->com_counter;
                } else {
                    $committed_counter[$key] = 0;
                }

            }

            $data["committed_counter"] = $committed_counter;
            $data["committed_courier"] = $committed_courier;
            $data["items"] = $items;

            $transaction = new StockReportExport($data, $startDate, $endDate);
            if ($transaction->collection()->count() > 0) {
                return Excel::download($transaction, 'Report Stock ('. $startDate . " to " . $endDate .').xlsx');
            }

            $request->session()->flash('error', 'No Data to Export');
            return redirect(url('/report/sales_report'));
                        
        } else {

            $items = DB::table('items as a')
            ->join('locations as b', 'b.item_id', 'a.id');
                
            // if ($startDate != null && $endDate != null) {
            //     $items = $items->whereDate('oi.created_at', '>=', $startDate)
            //         ->whereDate('oi.created_at', '<=', $endDate);
            // }

            $items = $items->select(
                'a.id', 
                'a.brand_name', 
                'a.item_code', 
                'b.courier as on_hand',
                'b.staff',
                'b.store',
                'b.counter',
                'b.courier',
                'b.store'
            )
            ->paginate(10, ['*'], 'page', $page);

            $links = $items->links();

            foreach($items as $key => $val){
                $db_courier = DB::select(DB::raw("SELECT SUM(order_items.quantity) as com_courier FROM orders JOIN order_items ON orders.id = order_items.order_id WHERE orders.dispensing_method = 'Delivery' AND order_items.myob_product_id = ".$val->id." AND orders.dispense_date >= '".$startDate.$startTime."' AND orders.dispense_date <= '".$endDate.$endTime."' AND orders.status_id IN (3, 4, 5) AND orders.return_timestamp IS NULL AND orders.deleted_at IS NULL AND order_items.deleted_at IS NULL;"));
                if ($db_courier[0]->com_courier) {
                    $committed_courier[$key] = $db_courier[0]->com_courier;
                } else {
                    $committed_courier[$key] = 0;
                }

                $db_counter = DB::select(DB::raw("SELECT SUM(order_items.quantity) as com_counter FROM orders JOIN order_items ON orders.id = order_items.order_id WHERE orders.dispensing_method = 'Walkin' AND order_items.myob_product_id = ".$val->id." AND orders.dispense_date >= '".$startDate.$startTime."' AND orders.dispense_date <= '".$endDate.$endTime."' AND orders.status_id IN (3, 4, 5) AND orders.return_timestamp IS NULL AND orders.deleted_at IS NULL AND order_items.deleted_at IS NULL;"));
                if ($db_counter[0]->com_counter) {
                    $committed_counter[$key] = $db_counter[0]->com_counter;
                } else {
                    $committed_counter[$key] = 0;
                }

            }

            $pdf = PDF::loadView('reports.report_stocks', compact('items','roles','startDate','endDate','links','page','committed_courier','committed_counter'));
            return $pdf->stream('patient_lists.pdf');

        }

    }
}
