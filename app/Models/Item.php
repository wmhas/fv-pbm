<?php

namespace App\Models;

use App\Models\Stock;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Item extends Model
{
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

    public function stocks ()
    {
        return $this->hasMany(Stock::class, 'item_id', 'id');
    }

    public function used_stock(){
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

}
