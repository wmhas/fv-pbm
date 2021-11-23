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
use Carbon\Carbon;

class TransactionsExport implements FromCollection, WithHeadings, WithStyles, WithColumnFormatting
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
            ->join("cards","cards.id","=","patients.card_id")
            ->join("prescriptions","prescriptions.order_id","=","orders.id")
            ->join("clinics","prescriptions.clinic_id","=","clinics.id")
            ->join("order_items","order_items.order_id","=","orders.id")
            ->join("items","items.id","=","order_items.myob_product_id")
            ->join("states","states.id","=","patients.state_id")
            ->whereIn('orders.status_id', [4, 5]);

        if ($this->startDate && $this->endDate){
            $order = $order->whereDate('orders.created_at', '>=', $this->startDate)
                    ->whereDate('orders.created_at', '<=', $this->endDate);
        } else {
            $order = $order->whereDate('orders.created_at', Carbon::today());
        }

        $order = $order->select("orders.id",
            "orders.dispense_date as dates",
            "orders.do_number", 
            "patients.identification as ic",
            "patients.full_name",
            "patients.address_1",
            "patients.address_2",
            "patients.address_3",
            "patients.postcode",
            "patients.city",
            "states.name",
            "prescriptions.rx_number",
            "prescriptions.rx_start",
            "prescriptions.rx_end", 
            "clinics.name as clinic", 
            "orders.dispensing_method",
            "items.brand_name as med",
            "order_items.quantity",
            "order_items.duration",
            "items.selling_price as unit_price",
            "order_items.price as total_price",
            "cards.type as type",
        )->orderBy('orders.created_at','DESC');

        if ($this->page) {
            $order = $order->paginate(10, ['*'], 'page', $this->page);
        } else {
            $order = $order->get();
        }


        $orders = [];

        $num = 1;

        if (count($order)>0){

            foreach ($order as $k => $v) {

                $address = "";

                if (!empty($v->address_1))
                    $address .= $v->address_1;
                if (!empty($v->address_2))
                    $address .= " " .$v->address_2;
                if (!empty($v->address_3))
                    $address .= " " .$v->address_3;
                if (!empty($v->postcode))
                    $address .= " " .$v->postcode;
                if (!empty($v->city))
                    $address .= " " .$v->city;
                if (!empty($v->name))
                    $address .= " " .$v->name;

                $orders[$k]['ORDER ID'] = $v->id;
                $orders[$k]['NO'] = $num;
                $orders[$k]['DATE']=$v->dates;
                $orders[$k]['DO NUMBER']=$v->do_number;
                $orders[$k]['IC']=$v->ic;
                $orders[$k]['FULLANME']=$v->full_name;
                $orders[$k]['ADDRESS']= trim($address);
                $orders[$k]['CLINIC']=$v->clinic;
                $orders[$k]['DISPENSING METHOD']=$v->dispensing_method;
                $orders[$k]['RX NUMBER']=$v->rx_number;
                $orders[$k]['RX DURATION']=$v->duration;
                $orders[$k]['MEDICINE']=$v->med;
                $orders[$k]['QTY'] = $v->quantity;
                $orders[$k]['UNIT PRICE'] = $v->unit_price;
                $orders[$k]['TOTAL PRICE'] = $v->total_price;
                $orders[$k]['STATUS'] = $v->type;
                
                if (!empty($orders[$k]['NO'])){
                    $num+=1;
                }
            }
        }
        
        return collect($orders);
    }

    public function headings(): array
    {
        return [
            'ORDER ID',
            'NO',
            'DISPENSE DATE',
            'DO NUMBER',
            'IC',
            'FULLNAME',
            'ADDRESS',
            'CLINIC',
            'DISPENSING METHOD',
            'RX NUMBER',
            'RX DURATION',
            'MEDICINE',
            'QTY',
            'UNIT PRICE',
            'TOTAL PRICE',
            'STATUS',
            'REMARKS',
        ];
    }

    public function styles(Worksheet $sheet)
    {
        $sheet->getStyle('A1:Q1')->getFont()->setBold(true);

        $last_row = $sheet->getHighestRow();
        $current_row = 2;
        $start_row = 2;
        $end_row = 2;
        $num = 1;
        $current_id = $sheet->getCell('A'.$current_row)->getValue();

        for ($current_row = 2; $current_row <= $last_row; $current_row++) {
            if ($sheet->getCell('A'.$current_row)->getValue() == $current_id) {
                $end_row = $current_row;
            } else {
                $this->merge($sheet, $start_row, $end_row, $num);

                 
                
                $num++;
                $start_row = $end_row = $current_row;
                $current_id = $sheet->getCell('A' .$current_row)->getValue();
            }

            $sheet->getColumnDimension('A')->setVisible(false);
        }
        $this->merge($sheet, $start_row, $end_row, $num);
    }

    public function merge($sheet, $start_row, $end_row, $num) {
        $sheet->mergeCells('A' .$start_row. ':A' .$end_row);
        $sheet->mergeCells('B' .$start_row. ':B' .$end_row);
        $sheet->mergeCells('C' .$start_row. ':C' .$end_row);
        $sheet->mergeCells('D' .$start_row. ':D' .$end_row);
        $sheet->mergeCells('E' .$start_row. ':E' .$end_row);
        $sheet->mergeCells('F' .$start_row. ':F' .$end_row);
        $sheet->mergeCells('G' .$start_row. ':G' .$end_row);
        $sheet->mergeCells('H' .$start_row. ':H' .$end_row);
        $sheet->mergeCells('I' .$start_row. ':I' .$end_row);
        $sheet->mergeCells('J' .$start_row. ':J' .$end_row);
        $sheet->mergeCells('P' .$start_row. ':P' .$end_row);
        $sheet->mergeCells('Q' .$start_row. ':Q' .$end_row);

        $sheet->setCellValue('B'.$start_row, $num); 
    }

    public function columnFormats(): array
    {
        return [
            'N' => NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1,
            'O' => NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1,
        ];
    }
}