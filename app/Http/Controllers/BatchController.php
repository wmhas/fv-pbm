<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use PDF;
use App\Models\Order;
use App\Models\Batch;
use App\Models\BatchOrder;

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
            'unbatches' => Batch::where('batch_status', 'unbatch')->paginate(5),
            'batches' => Batch::where('batch_status', 'batched')->paginate(5),
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

    public function batch_order(Order $order, Request $request)
    {
        $batchperson = $request->input('batchperson');
        $order->status_id = 5;
        $order->save();
        if ($order->patient->tariff_id == 1 || $order->patient->tariff_id == 2) {
            $tariff = 1;
        } else {
            $tariff = 3;
        }

        $batch =  Batch::where('tariff_id', $tariff)->where('batch_status', 'unbatch')->latest()->first();
        $count_batch=Batch::count();
        //only find unbatch status so it wont go to batched batch
        if ($batch == null) {
            $batch =  Batch::create([
                'batch_no' => 'B' .str_pad($count_batch + 1, 6, "0", STR_PAD_LEFT),
                'batch_status' => 'unbatch',
                'tariff_id' =>  $tariff
            ]);
        }

        if ($batch_order = BatchOrder::where('batch_id', $batch->id)->get()->count() > 10) {
            $batch =  Batch::create([
                'batch_no' => 'B' .str_pad($count_batch + 1, 6, "0", STR_PAD_LEFT),
                'batch_status' => 'unbatch',
                'tariff_id' =>  $tariff
            ]);
        }
        BatchOrder::create([
            'order_id' => $order->id,
            'batch_id' => $batch->id,
            'tariff_id' => $tariff,
            'batchperson_id' => $batchperson
        ]);

        return redirect()->action('BatchController@show_batch', [
            'batch' => $batch->id
        ]);
    }

    public function show_batch(Batch $batch)
    {
        $batch_orders = BatchOrder::where('batch_id', $batch->id)->get();
        $roles = DB::table('model_has_roles')->join('users', 'model_has_roles.model_id', '=', 'users.id')->where("users.id", auth()->id())->first();
        return view('batch.batch', [
            'batches' => $batch_orders,
            'group' => $batch,
            'roles' => $roles
        ]);
    }

    public function changeStatus(Batch $batch)
    {
        $batch->batch_status = 'batched';
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
            'unbatches' => Batch::where('batch_status', 'unbatch')->paginate(5),
            'batches' => Batch::where('id', 'like', '%' . strtoupper($keyword) . '%')
                ->where('batch_status', 'batched')->paginate(5),
            'keyword' => $keyword,
        ]);
    }
}
