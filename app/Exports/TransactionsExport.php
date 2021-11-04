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
            "orders.updated_at as dates",
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
        )->get();

        $orders = [];

        $num = 1;

        if (count($order)>0){

            foreach ($order as $k => $v) {

                $duration = floor(abs(strtotime($v->rx_end) - strtotime($v->rx_start)) / (60 * 60 * 24));

                $oi = OrderItem::with("items")->where("order_id",$v->id)->get();
                
                if (count($oi)>0) {
                    foreach ($oi as $koi => $voi) {
                        $orders[$k]['NO'] = $num;
                        $orders[$k]['DATE']=$v->dates;
                        $orders[$k]['DO NUMBER']=$v->do_number;
                        $orders[$k]['IC']=$v->ic;
                        $orders[$k]['FULLANME']=$v->full_name;
                        $orders[$k]['ADDRES']=$v->address;
                        $orders[$k]['RX NUMBER']=$v->rx_number;
                        $orders[$k]['RXDURATION']=$duration;
                        $orders[$k]['DISPENSED BY']=$v->dispensing_by;
                        $orders[$k]['MEDICINE']=$v->med;
                        $orders[$k]['QTY'] = $voi->quantity;
                        $orders[$k]['UNIT PRICE'] = $v->unit_price;
                        $orders[$k]['TOTAL PRICE'] = $v->total_price;
                        $orders[$k]['STATUS'] = $v->type;
                    }

                    if (!empty($orders[$k]['NO'])){
                        $num+=1;
                    }
                }
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
            'RX DURATION',
            'DISPENSED BY',
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
        $sheet->getStyle('A1:N1')->getFont()->setBold(true);
        $column = 'C';
        $lastRow = $sheet->getHighestRow();
        $start = 2;
        $first = $sheet->getCell('C2')->getValue();
        $x = $start;
        $no = 1;
        for ($row = 1; $row <= $lastRow; $row++) {
            if($row > 1){
                if($first != $sheet->getCell($column.$row)->getValue()){
                    $z = $start-1;
                    $sheet->mergeCells('A'.$x.':A'.$z);
                    $sheet->mergeCells('B'.$x.':B'.$z);
                    $sheet->mergeCells('C'.$x.':C'.$z);
                    $sheet->mergeCells('D'.$x.':D'.$z);
                    $sheet->mergeCells('E'.$x.':E'.$z);
                    $sheet->mergeCells('F'.$x.':F'.$z);
                    $sheet->mergeCells('G'.$x.':G'.$z);
                    $sheet->mergeCells('H'.$x.':H'.$z);
                    $sheet->mergeCells('M'.$x.':M'.$z);
                    $sheet->mergeCells('N'.$x.':N'.$z);
                    $x = $start;
                    $first = $sheet->getCell($column.$row)->getValue();
                    $no++;
                    $sheet->setCellValue('A'.$x, $no);
                }
                $start++;
            }
        }
    }

    public function columnFormats(): array
    {
        return [
            'K' => NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1,
            'L' => NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1,
        ];
    }
}