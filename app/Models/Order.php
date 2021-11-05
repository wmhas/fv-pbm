<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use DB;
use Carbon\Carbon;

class Order extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'patient_id', 'status_id','total_amount', 'do_number', 'dispensing_by', 'dispense_date', 'dispensing_method',
        'rx_interval', 'order_original_filename', 'order_document_path', 'salesperson_id'
    ];


    function patient()
    {
        return $this->belongsTo(Patient::class, 'patient_id', 'id');
    }

    public function status()
    {
        return $this->belongsTo(Status::class , 'status_id', 'id');
    }

    public function orderitem()
    {
        return $this->hasMany(OrderItem::class , 'order_id', 'id')->with('items');
    }

    public function prescription()
    {
        return $this->hasOne(Prescription::class , 'order_id');
    }

    public function delivery()
    {
        return $this->hasOne(Delivery::class, 'order_id', 'id');
    }

    public function salesperson()
    {
        return $this->belongsTo(SalesPerson::class , 'salesperson_id', 'id');
    }

    public static function getorder($startDate,$endDate,$page)
    {
        $order = Order::join("patients","patients.id","=","orders.patient_id")
            ->join("cards","cards.id","=","patients.card_id")
            ->join("prescriptions","prescriptions.order_id","=","orders.id")
            ->join("order_items","order_items.order_id","=","orders.id")
            ->join("items","items.id","=","order_items.myob_product_id")
            ->join("states","states.id","=","patients.state_id")
            ->whereIn('status_id', [4, 5]);

        if ($startDate && $endDate){
            $order = $order->whereDate('orders.created_at', '>=', $startDate)
                    ->whereDate('orders.created_at', '<=', $endDate);
        }   else {
            $order = $order->whereDate('orders.created_at', Carbon::today());
        }

        $order = $order->select("orders.id",
            "orders.updated_at as tanggal",
            "orders.do_number", 
            "patients.identification as ic",
            "patients.full_name",
            DB::raw('CONCAT(patients.address_1,", ",patients.address_2,", ",patients.postCode,", ",states.name) as address'),
            "prescriptions.rx_number",
            "prescriptions.rx_start",
            "prescriptions.rx_end", 
            "orders.dispensing_by",
            "items.brand_name as med",
            "order_items.quantity",
            "items.selling_price as unit_price",
            "order_items.price as total_price",
            "cards.type as type",
        )->orderBy('orders.created_at','DESC')->paginate(10, ['*'], 'page', $page);

        $orders = [];

        $num = 1;

        if (count($order)>0){

            foreach ($order as $k => $v) {

                $duration = floor(abs(strtotime($v->rx_end) - strtotime($v->rx_start)) / (60 * 60 * 24));

                $oi = OrderItem::with("items")->where("order_id",$v->id)->get();
                
                if (count($oi)>0) {
                    foreach ($oi as $koi => $voi) {
                        $orders[$k]['NO'] = $num;
                        $orders[$k]['DATE']=$v->tanggal;
                        $orders[$k]['DONUMBER']=$v->do_number;
                        $orders[$k]['IC']=$v->ic;
                        $orders[$k]['FULLANME']=$v->full_name;
                        $orders[$k]['ADDRES']=$v->address;
                        $orders[$k]['RXNUMBER']=$v->rx_number;
                        $orders[$k]['RXDURATION']=$duration;
                        $orders[$k]['DISPENSEDBY']=$v->dispensing_by;
                        $orders[$k]['MEDICINE']=$v->med;
                        $orders[$k]['QTY'] = $voi->quantity;
                        $orders[$k]['UNITPRICE'] = $v->unit_price;
                        $orders[$k]['TOTALPRICE'] = $v->total_price;
                        $orders[$k]['STATUS'] = $v->type;
                    }

                    if (!empty($orders[$k]['NO'])){
                        $num+=1;
                    }
                }
            }

        }

        $data["collectOrder"] = collect($orders);
        $data["links"] = $order->links();
        return $data;
    }

}
