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
                $orders[$k]['ORDER ID'] = $v->order->id;
                $orders[$k]['No'] = $num;
                $orders[$k]['DO Number']=$v->order->do_number;
                $orders[$k]['Patient Name']=$v->order->patient->full_name;
                $orders[$k]['Patient IC']=$v->order->patient->identification;
                $orders[$k]['Patient Pensioner No']=$v->order->patient->card->army_pension;
                $orders[$k]['Agency']=$v->order->patient->tariff->name;
                $orders[$k]['Quotation Date']=$v->order->dispense_date;
                $orders[$k]['Item']= $v->brand_name;
                $orders[$k]['Qty']= $v->quantity;
                $orders[$k]['Total Amount (RM)'] = $v->order->total_amount;
                $orders[$k]['Status'] = $v->order->patient->card->type;
                $num++;
            }

        }
        
        return collect($orders);
    }

    public function headings(): array
    {
        return [
            'ORDER ID',
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
        // $column = 'B';
        // $lastRow = $sheet->getHighestRow();
        // $start = 2;
        // $first = $sheet->getCell('B2')->getValue();
        // $x = $start;
        // $no = 1;
        // for ($row = 1; $row <= $lastRow; $row++) {
        //     if($row > 1){
        //         if($first != $sheet->getCell($column.$row)->getValue()){
        //             $z = $start-1;
        //             $sheet->mergeCells('A'.$x.':A'.$z);
        //             $sheet->mergeCells('B'.$x.':B'.$z);
        //             $sheet->mergeCells('C'.$x.':C'.$z);
        //             $sheet->mergeCells('D'.$x.':D'.$z);
        //             $sheet->mergeCells('E'.$x.':E'.$z);
        //             $sheet->mergeCells('F'.$x.':F'.$z);
        //             $sheet->mergeCells('G'.$x.':G'.$z);
        //             $sheet->mergeCells('J'.$x.':J'.$z);
        //             $sheet->mergeCells('K'.$x.':K'.$z);
        //             $x = $start;
        //             $first = $sheet->getCell($column.$row)->getValue();
        //             $no++;
        //             $sheet->setCellValue('A'.$x, $no);
        //         }
        //         $start++;
        //     }
        // }
        // $sheet->setCellValue('A'.($lastRow+1), 'GRAND TOTAL (RM)');
        // $sheet->mergeCells('A'.($lastRow+1).':I'.($lastRow+1));

        // $sheet->setCellValue('J'.($lastRow+1), '=SUM(J2:J'.$lastRow.')');
        // $sheet->getStyle('A'.($lastRow+1).':J'.($lastRow+1))->getFont()->setBold(true);

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
        $sheet->mergeCells('K' .$start_row. ':K' .$end_row);
        $sheet->mergeCells('L' .$start_row. ':L' .$end_row);

        $sheet->setCellValue('B'.$start_row, $num);
    }

    public function columnFormats(): array
    {
        return [
            'K' => NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1,
        ];
    }
}