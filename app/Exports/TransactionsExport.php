<?php 

namespace App\Exports;

use App\Models\Order;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\FromCollection;
use Illuminate\Support\Facades\DB;

class TransactionsExport implements FromCollection, WithHeadings, WithMapping
{

    private $startDate = false;
    private $endDate = false;

    public function __construct($startDate, $endDate) 
    {
        $this->startDate = $startDate;
        $this->endDate = $endDate;
    }

    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        $order = Order::join("patients","patients.id","=","orders.patient_id")
        ->join("cards","cards.id","=","patients.card_id")
        ->join("prescriptions","prescriptions.order_id","=","orders.id")
        ->join("order_items","order_items.order_id","=","orders.id")
        ->join("items","items.id","=","order_items.myob_product_id");

        if ($this->startDate && $this->endDate){
            $order = $order->whereDate('orders.created_at', '>=', $this->startDate)
                    ->whereDate('orders.created_at', '<=', $this->endDate);
        }

        $order = $order->select("orders.id", 
            "orders.created_at as dates", 
            "orders.do_number", 
            "patients.ic_original_filename as ic", 
            "patients.full_name",
            "patients.address_1 as address",
            "prescriptions.rx_number", 
            "orders.dispensing_by",
            "items.brand_name as med",
            "order_items.quantity")->groupBy("orders.id")->get();

        return $order;
    }

    public function headings(): array
    {
        return [
            'NO',
            'DATE',
            'DO NUMBER',
            'IC',
            'FULLANME',
            'ADDRES',
            'RX NUMBER',
            'DISPENSED BY',
            'MEDICINE',
            'QTY',
        ];
    }

    public function map($transaction): array
    {
        return [
            $transaction->id,
            $transaction->dates,
            $transaction->do_number,
            $transaction->ic,
            $transaction->full_name,
            $transaction->address,
            $transaction->rx_number,
            $transaction->dispensing_by,
            $transaction->med,
            $transaction->quantity,
        ];
    }
}