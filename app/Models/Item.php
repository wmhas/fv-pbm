<?php

namespace App\Models;

// use App\Models\Detail;
use App\Models\Stock;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Item extends Model
{
    //ITEMS
    protected $fillable = [
        'id', 'item_code', 'brand_name', 'generic_name', 'description', 'indication', 'indikasi',
        'instruction', 'expiry_date', 'purchase_price', 'purchase_uom', 'purchase_quantity', 'stock_level',
        'selling_price', 'selling_uom', 'reorder_quantity', 'reorder_supplier', 'tariff_id', 'frequency_id', 'formula_id'
    ];

    public function tariff()
    {
        return $this->belongsTo(Tariff::class, 'tariff_id', 'id');
    }

    public function formula()
    {
        return $this->belongsTo(Formula::class, 'formula_id', 'id');
    }

    public function frequency()
    {
        return $this->belongsTo(Frequency::class, 'frequency_id', 'id');
    }

    public function stock_level()
    {
        $stock_level = Stock::selectRaw("SUM(quantity) as quantity, item_id")->where('item_id', $this->id)->groupBy('item_id')->first();
        return $stock_level;
    }

    public function used_stock(){
        // $used_stock = Stock::selectRaw("SUM(quantity) as quantity, item_id")
        //     ->join
        //     ->where('item_id', $this->id)
        //     ->where('source', 'purchase')
        //     ->groupBy('item_id')->first();
        $used_stock = DB::table('order_items as a')
            ->join('orders as b', 'a.order_id', 'b.id')
            ->join('stocks as c', 'a.id', 'c.source_id')
            ->selectRaw("SUM(c.quantity) as quantity, c.item_id")
            ->where('c.item_id', $this->id)
            ->whereIn('c.source', ['sale','return'])
            ->whereIn('b.status_id', [4,5])
            ->whereNull('b.deleted_at')
            ->groupBy('c.item_id')->first();

        return $used_stock;
    }
    protected $table = 'items';

    public function order_items ()
    {
        return $this->hasMany(OrderItem::class, 'myob_product_id', 'id');
    }

    public function total_price ($date = null)
    {
        $date = ($date) ?: date('Y-m-d');
        $id = $this->id;
        $totalPrice = 0;
        $orders = Order::whereHas('orderItem', function ($orderItem) use ($id) {
                $orderItem->where('myob_product_id', $id);
            })
            ->with(['orderItem'])
            ->whereDate('created_at', $date)
            ->get();
        foreach ($orders AS $order) {
            foreach($order->orderItem AS $orderItem) {
                $totalPrice += $orderItem->quantity * $orderItem->price;
            }
        }
        return $totalPrice;
    }

    //MYOB
    // protected $fillable = [
    //     'ItemID'
    // ];

    // public function tariff()
    // {
    //     return $this->belongsTo(Tariff::class, 'tariff_id', 'id');
    // }

    // public function detail()
    // {
    //     return $this->hasOne(Detail::class, 'myob_product_id', 'ItemNumber');
    // }

    // public function details($which)
    // {
    //     switch ($which) {
    //         case 1:
    //             $detail = Detail::where('myob_product_id', $this->ItemNumber)->first();
    //             return $detail->instruction;
    //             break;
    //         case 2:
    //             $detail = Detail::where('myob_product_id', $this->ItemNumber)->first();
    //             return $detail->indikasi;
    //             break;
    //         case 3:
    //             $detail = DB::table('myob_product_details as a')->join('frequencies as b', 'b.id', '=', 'a.frequency_id')->select('b.name')->where('a.myob_product_id', $this->ItemNumber)->first();
    //             return $detail->name;
    //             break;
    //         default:
    //             return null;
    //             break;
    //     }
    // }

    // public function stock_level(){
    //     $stock_level = Stock::selectRaw("SUM(quantity) as quantity, item_id")->where('item_id', $this->ItemID)->groupBy('item_id')->first();
    //     return $stock_level;
    // }
    // protected $table = 'myob_products';

}
