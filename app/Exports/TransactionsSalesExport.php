<?php 

namespace App\Exports;

use App\Models\Order;
use App\Models\Item;
use App\Models\OrderItem;
use Maatwebsite\Excel\Concerns\WithColumnFormatting;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\Exportable;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class TransactionsSalesExport implements FromCollection, WithHeadings, WithStyles, WithColumnFormatting
{

    use Exportable;
    private $startDate = false;
    private $endDate = false;

    public function __construct($startDate, $endDate, $page = null)
    {
        $this->startDate = $startDate;
        $this->endDate = $endDate;
        $this->page = $page;
    }

    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        $order = Order::join("patients","patients.id","=","orders.patient_id")
            ->leftjoin("tariffs","tariffs.id","=","patients.tariff_id")
            ->join("cards","cards.id","=","patients.card_id")
            ->join("prescriptions","prescriptions.order_id","=","orders.id")
            ->join("order_items","order_items.order_id","=","orders.id")
            ->join("items","items.id","=","order_items.myob_product_id")
            ->join("states","states.id","=","patients.state_id")
            ->whereIn('orders.status_id', [4, 5]);
        
        if ($this->startDate && $this->endDate){
            $order = $order->whereDate('orders.created_at', '>=', $this->startDate)
                    ->whereDate('orders.created_at', '<=', $this->endDate);
        }

        $order = $order->select("orders.id",
            "orders.created_at as dates",
            "orders.do_number", 
            "patients.identification as ic",
            "patients.full_name",
            DB::raw('CONCAT(patients.address_1,", ",patients.address_2,", ",patients.postCode,", ",states.name) as address'),
            "prescriptions.rx_number", 
            "orders.dispensing_by",
            DB::raw("(CASE WHEN patients.tariff_id IS NOT NULL THEN tariffs.name ELSE 'no panel' END) as panel"),
            "orders.total_amount",
            DB::raw("(CASE WHEN orders.status_id = 4 THEN 'Complete Order' ELSE 'Batch Order' END) as status"),
        )->orderBy('orders.created_at', 'DESC');

        if ($this->page) {
            $order = $order->paginate(10, ['*'], 'page', $this->page);
        } else {
            $order = $order->get();
        }

        $orders = [];

        $num = 1;

        if (count($order)>0){
            foreach ($order as $k => $v) {
                $orders[$k]['NO'] = $num;
                $orders[$k]['DATE']=$v->dates;
                $orders[$k]['DO NUMBER']=$v->do_number;
                $orders[$k]['IC']=$v->ic;
                $orders[$k]['FULLANME']=$v->full_name;
                $orders[$k]['ADDRES']=$v->address;
                $orders[$k]['RX NUMBER']=$v->rx_number;
                $orders[$k]['DISPENSED BY']=$v->dispensing_by;
                $orders[$k]['PANEL']=$v->panel;
                $orders[$k]['TOTAL AMOUNT'] = $v->total_amount;
                $orders[$k]['STATUS'] = $v->status;

                $num++;
            }
        }
        
        return collect($orders);
    }

    public function headings(): array
    {
        return [
            'NO',
            'DATE',
            'DO NUMBER',
            'IC',
            'FULLNAME',
            'ADDRES',
            'RX NUMBER',
            'DISPENSED BY',
            'PANEL',
            'TOTAL AMOUNT',
            'STATUS',
        ];
    }

    public function styles(Worksheet $sheet)
    {
        $sheet->getStyle('A1:K1')->getFont()->setBold(true);
    }

    public function columnFormats(): array
    {
        return [
            'J' => NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1,
        ];
    }
}