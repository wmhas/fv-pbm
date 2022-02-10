<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use PDF;
use App\Models\Order;
use App\Models\Batch;
use App\Models\NewBatch;
use App\Models\BatchOrder;
use App\Exports\NewBatchExport;
use Excel;
use Carbon\Carbon;

class BatchController extends Controller
{
    function __construct()
    {
        $this->middleware('permission:batch', [
            'only' => [
                'index', 'show', 'pending', 'batch_order', 'show_batch', 'changeStatus', 'search_batch'
            ]
        ]);
    }
    public function index()
    {
        $keyword = null;
        $roles = DB::table('model_has_roles')->join('users', 'model_has_roles.model_id', '=', 'users.id')->where("users.id", auth()->id())->first();
        return view('batch.index', [
            'roles' => $roles,
            'batching' => NewBatch::where('batch_status', 'batching')->where('deleted_at', null)->with(['orders', 'sales_person'])->paginate(5),
            'batched' => NewBatch::where('batch_status', 'batched')->where('deleted_at', null)->with(['orders', 'sales_person'])->paginate(5),
            'keyword' => $keyword,
        ]);
    }

    public function show()
    {
        $roles = DB::table('model_has_roles')->join('users', 'model_has_roles.model_id', '=', 'users.id')->where("users.id", auth()->id())->first();
        return view('batch.view', compact('roles'));
    }

    public function pending()
    {
        $roles = DB::table('model_has_roles')->join('users', 'model_has_roles.model_id', '=', 'users.id')->where("users.id", auth()->id())->first();
        return view('batch.pending', compact('roles'));
    }

    // public function getBatchNo() {
    //     $increment = 1;
     
    //     do {
    //         $count_batch = DB::table('new_batches')->where('batch_no', '!=', '')->whereNull('deleted_at')->count();
    //         $batch_no = 'B' . str_pad($count_batch + $increment, 6, "0", STR_PAD_LEFT);

    //         $exists = NewBatch::where('batch_no', $batch_no)->first();

    //         if ($exists)
    //             $increment++;

    //     } while ($exists);

    //     return $batch_no;
    // }

    public function batch_order(Order $order, Request $request) {        
        
        if ($order->patient->tariff_id == 1 || $order->patient->tariff_id == 2) {
            $tariff = 1;
        } elseif ($order->patient->tariff_id == 3)  {
            $tariff = 3;
        } else {
            return redirect()->back()
                ->with(['status' => false, 'message' => 'THIS PATIENT DOES NOT HAVE A TARIFF, PLEASE UPDATE']);
        }
        
        $batch_person = $request->input('batchperson');
        $batch_status = 'batching';
        $patient_status = $order->patient->card->type;

        if ($patient_status == "Veteran Berpencen" || $patient_status == "JPA Berpencen") {
            $patient_status = 1;
        } else {
            $patient_status = 0;
        }

        $total_batch = NewBatch::count();
        $batch = NewBatch::where('batch_person', $batch_person)
            ->where('batch_status', 'batching')
            ->where('tariff', $tariff)
            ->where('patient_status', $patient_status)
            ->where('deleted_at', NULL)
            ->first();
        
        if ($batch == NULL) {
            $batch = NewBatch::create([
                'batch_no' => 'B' .str_pad($total_batch + 1, 6, "0", STR_PAD_LEFT),
                'batch_person' => $batch_person,
                'batch_status' => $batch_status,
                'tariff' => $tariff,
                'patient_status' => $patient_status
            ]);
        }

        $batch_id = $batch->id;

        $order->status_id = 5;
        $order->batch_id = $batch_id;
        $order->save();

        return redirect()->action('BatchController@show_batch', [
            'batch' => $batch->id
        ]);
    }

    public function show_batch(NewBatch $batch) {
        $orders = Order::where('batch_id', $batch->id)->get();

        $roles = DB::table('model_has_roles')->join('users', 'model_has_roles.model_id', '=', 'users.id')->where("users.id", auth()->id())->first();
        return view('batch.batch', [
            'orders' => $orders,
            'batch' => $batch,
            'roles' => $roles
        ]);
    }

    public function export_batch_excel(Request $request)
    {

        if ($request->post('exportable') == "yes") {
            $batch_id = $request->post('batch_id');
            $batch_status = $request->post('batch_status');
            $batch_no = $request->post('batch_no');

            $export = new NewBatchExport($batch_id);
            
            return Excel::download($export, 'Batch ' . $batch_no . '.xlsx');
        }

        $request->session()->flash('error', 'No Data to Export');
        return redirect(url('/batch/'.$batch_id.'/batch_list'));
    }

    public function changeStatus(NewBatch $batch)
    {
        $batch->batch_status = 'batched';
        $batch->submission_date = now();
        $batch->save();
        return redirect()->action('BatchController@index')->with(['status' => true, 'message' => 'Order Batched!']);
    }

    public function search_batch(Request $request)
    {
        $keyword = $request->get('keyword');
        $keyword = preg_replace("/[^a-zA-Z0-9 ]/", "", $keyword);
        $roles = DB::table('model_has_roles')->join('users', 'model_has_roles.model_id', '=', 'users.id')->where("users.id", auth()->id())->first();
        return view('batch.index', [
            'roles' => $roles,
            'batching' => NewBatch::where('batch_status', 'batching')->where('deleted_at', null)->with(['orders', 'sales_person'])->paginate(5),
            'batched' => NewBatch::where('batch_status', 'batched')->where('deleted_at', null)->where('batch_no', 'like', '%' . strtoupper($keyword) . '%')->with(['orders', 'sales_person'])->paginate(5),
            'keyword' => $keyword,
        ]);
    }

    public function remove_order($batch, $order) {
        $order = Order::where('id', $order)->first();
        $order->status_id = 4;
        $order->batch_id = null;
        $order->update();

        $batch = NewBatch::where('id', $batch)->first();
        
        if (sizeOf($batch->orders) == 0) {
            $batch->deleted_at = Carbon::now();
            $batch->update();
            
            return redirect('/batch'); 
        }
        
        return redirect('/batch/' . $batch->id . '/batch_list');
    }

    public function delete_batch($batch) {
        $orders = Order::where('batch_id', $batch)->get();
        foreach($orders as $order) {
            $order->status_id = 4;
            $order->batch_id = null;
            $order->update();
        }

        $batch = NewBatch::where('id', $batch)->first();
        $batch->deleted_at = Carbon::now();
        $batch->update();
        
        return redirect('/batch'); 
    }
}
