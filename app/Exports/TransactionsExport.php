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
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use Carbon\Carbon;

class TransactionsExport implements FromCollection, WithHeadings, WithStyles, WithColumnFormatting
{

    use Exportable;
    private $startDate = false;
    private $endDate = false;
    private $grand_total;

    public function __construct($startDate, $endDate, $page = null)
    {
        $this->startDate = $startDate;
        $this->endDate = $endDate;
        $this->page = $page;
        $this->grand_total = 0;
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
            ->whereIn('orders.status_id', [3, 4, 5])
            ->whereNull('orders.deleted_at')
            ->whereNull('order_items.deleted_at');

        if ($this->startDate && $this->endDate){
            $order = $order->whereDate('orders.dispense_date', '>=', $this->startDate)
                    ->whereDate('orders.dispense_date', '<=', $this->endDate);
        } else {
            $order = $order->whereDate('orders.dispense_date', Carbon::today());
        }

        $order = $order->select("orders.id",
            "orders.dispense_date as dates",
            "orders.do_number", 
            "patients.identification as ic",
            "cards.army_pension as army",
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
        )->orderBy('orders.dispense_date','DESC');

        if ($this->page) {
            $order = $order->paginate(10, ['*'], 'page', $this->page);
        } else {
            $order = $order->get();
        }


        $orders = [];

        $num = 1;
        $this->grand_total = 0;

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
                $orders[$k]['ARMY NO']=$v->army;
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

                $this->grand_total += $v->total_price;
                
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
            [
                'ORDER ID',
                'NO',
                'DISPENSE DATE',
                'DO NUMBER',
                'IC',
                'ARMY NO',
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
            ],
            [
                'ORDER ID',
                'NO',
                'DISPENSE DATE',
                'DO NUMBER',
                'IC',
                'ARMY NO',
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
            ],
            [
                'ORDER ID',
                'NO',
                'DISPENSE DATE',
                'DO NUMBER',
                'IC',
                'ARMY NO',
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
            ],
            [
                'ORDER ID',
                'NO',
                'DISPENSE DATE',
                'DO NUMBER',
                'IC',
                'ARMY NO',
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
            ],
            [
                'ORDER ID',
                'NO',
                'DISPENSE DATE',
                'DO NUMBER',
                'IC',
                'ARMY NO',
                'FULLNAME',
                'ADDRESS',
                'CLINIC',
                'DISPENSING METHOD',
                'RX NUMBER',
                'RX DURATION',
                'MEDICINE',
                'QUANTITY',
                'UNIT PRICE (RM)',
                'TOTAL PRICE (RM)',
                'STATUS',
                'REMARKS',
            ],
        ];
    }

    public function styles(Worksheet $sheet)
    {
        $right = array(
            'alignment' => array(
                'horizontal' => Alignment::HORIZONTAL_RIGHT,
                'vertical' => Alignment::VERTICAL_CENTER,
            )
        );

        $sheet->getStyle('A5:R5')->getFont()->setBold(true);

        $last_row = $sheet->getHighestRow();
        $current_row = 6;
        $start_row = 6;
        $end_row = 6;
        $num = 1;
        $current_id = $sheet->getCell('A'.$current_row)->getValue();

        for ($current_row = 6; $current_row <= $last_row; $current_row++) {
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

        for ($i = 1; $i < 5; $i++) {
            for ($j = 'A'; $j < 'S'; $j++) {
                $sheet->setCellValue($j.$i, '');
            }
        }

        $sheet->setCellValue('B2', 'Start Date :');
        $sheet->setCellValue('B3', 'End Date :');
        $sheet->setCellValue('D2', $this->startDate);
        $sheet->setCellValue('D3', $this->endDate);

        $sheet->mergeCells('B2:C2');
        $sheet->mergeCells('B3:C3');

        $sheet->getStyle('B2:B3')->getFont()->setBold(true);
        $sheet->getStyle('B2:B3')->applyFromArray($right);

        $sheet->getColumnDimension('B')->setAutoSize(true);
        $sheet->getColumnDimension('C')->setAutoSize(true);
        $sheet->getColumnDimension('D')->setAutoSize(true);
        $sheet->getColumnDimension('E')->setAutoSize(true);
        $sheet->getColumnDimension('F')->setAutoSize(true);
        $sheet->getColumnDimension('G')->setAutoSize(true);
        $sheet->getColumnDimension('H')->setAutoSize(true);
        $sheet->getColumnDimension('I')->setAutoSize(true);
        $sheet->getColumnDimension('J')->setAutoSize(true);
        $sheet->getColumnDimension('K')->setAutoSize(true);
        $sheet->getColumnDimension('L')->setAutoSize(true);
        $sheet->getColumnDimension('M')->setAutoSize(true);
        $sheet->getColumnDimension('N')->setAutoSize(true);
        $sheet->getColumnDimension('O')->setAutoSize(true);
        $sheet->getColumnDimension('P')->setAutoSize(true);
        $sheet->getColumnDimension('Q')->setAutoSize(true);
        $sheet->getColumnDimension('R')->setAutoSize(true);

        $lastRow = $sheet->getHighestRow() + 1;

        $sheet->getStyle('B' . $lastRow . ':P' . $lastRow)->getFont()->setBold(true);
        $sheet->getStyle('B' . $lastRow)->applyFromArray($right);
        $sheet->mergeCells('B' . $lastRow . ':O' . $lastRow);
        $sheet->setCellValue('B' . $lastRow, 'GRAND TOTAL :');
        $sheet->setCellValue('P' . $lastRow, $this->grand_total);
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
        $sheet->mergeCells('K' .$start_row. ':K' .$end_row);
        $sheet->mergeCells('Q' .$start_row. ':Q' .$end_row);
        $sheet->mergeCells('R' .$start_row. ':R' .$end_row);

        $sheet->setCellValue('B'.$start_row, $num); 
    }

    public function columnFormats(): array
    {
        return [
            'O' => NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1,
            'P' => NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1,
        ];
    }
}