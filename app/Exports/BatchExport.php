<?php 

namespace App\Exports;

use App\Models\Order;
use App\Models\Item;
use App\Models\OrderItem;
use App\Models\BatchOrder;
use Maatwebsite\Excel\Concerns\WithColumnFormatting;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\Exportable;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class BatchExport implements FromCollection, WithHeadings, WithStyles, WithColumnFormatting
{

    use Exportable;

    private $batch_id;

    public function __construct($batch_id) 
    {
        $this->batch_id = $batch_id;
    }

    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        $order = BatchOrder::join('order_items','order_items.order_id','=','batch_orders.order_id')
            ->join('items','items.id','=','order_items.myob_product_id')
            ->where("batch_id", $this->batch_id)
            ->get();

        $orders = [];

        $num = 1;

        if (count($order)>0){

            foreach ($order as $k => $v) {
                $orders[$k]['No'] = $num;
                $orders[$k]['DO Number']=$v->order->do_number;
                $orders[$k]['Patient Name']=$v->order->patient->full_name;
                $orders[$k]['Patient IC']=$v->order->patient->identification;
                $orders[$k]['Patient Pensioner No']=$v->order->patient->card->army_pension;
                $orders[$k]['Agency']=$v->order->patient->tariff->name;
                $orders[$k]['Quotation Date']=$v->order->dispense_date;
                $orders[$k]['Item']= $v->brand_name;
                $orders[$k]['Qty']= $v->quantity;
                $orders[$k]['Total Price (RM)'] = $v->order->total_amount;
                $orders[$k]['Status'] = $v->order->patient->card->type;
                $num++;
            }

        }
        
        return collect($orders);
    }

    public function headings(): array
    {
        return [
            'NO',
            'DO Number',
            'Patient Name',
            'Patient IC',
            'Patient Pensioner No',
            'Agency',
            'Quotation Date',
            'Item',
            'Qty',
            'Total Price (RM)',
            'Status'
        ];
    }

    public function styles(Worksheet $sheet)
    {
        $sheet->getStyle('A1:M1')->getFont()->setBold(true);
        $column = 'B';
        $lastRow = $sheet->getHighestRow();
        $start = 2;
        $first = $sheet->getCell('B2')->getValue();
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
                    $sheet->mergeCells('J'.$x.':J'.$z);
                    $sheet->mergeCells('K'.$x.':K'.$z);
                    $x = $start;
                    $first = $sheet->getCell($column.$row)->getValue();
                    $no++;
                    $sheet->setCellValue('A'.$x, $no);
                }
                $start++;
            }
        }
        $sheet->setCellValue('A'.($lastRow+1), 'GRAND TOTAL (RM)');
        $sheet->mergeCells('A'.($lastRow+1).':I'.($lastRow+1));

        $sheet->setCellValue('J'.($lastRow+1), '=SUM(J2:J'.$lastRow.')');
        $sheet->getStyle('A'.($lastRow+1).':J'.($lastRow+1))->getFont()->setBold(true);
    }

    public function columnFormats(): array
    {
        return [
            'K' => NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1,
            'L' => NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1,
        ];
    }
}