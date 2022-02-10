<?php

namespace App\Exports;

use App\Models\NewBatch;
use App\Models\Order;
use Maatwebsite\Excel\Concerns\WithColumnFormatting;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use Illuminate\Support\Facades\DB;

class NewBatchExport implements WithColumnFormatting, WithHeadings, FromCollection, WithStyles 
{
    use Exportable;

    private $batch_id;
    private $grand_total;
    private $batch_no;
    private $batch_person;
    private $submission_date;
    private $payor;
    private $patient_status;

    public function __construct($batch_id) {
        $this->batch_id = $batch_id;
        $this->grand_total = 0;
    }

    /** 
    * @return \Illuminate\Support\Collection
    */
    public function collection() {

        // get batch info
        $batch = NewBatch::join('sales_persons', 'new_batches.batch_person', '=', 'sales_persons.id')
            ->where('new_batches.id', '=', $this->batch_id)
            ->select(
                'new_batches.batch_no',
                'new_batches.patient_status',
                'new_batches.submission_date',
                'new_batches.tariff',
                'sales_persons.name as batch_person'
            )
            ->first();
        
        $this->batch_no = $batch->batch_no;
        $this->batch_person = strtoupper($batch->batch_person);
        $this->submission_date = date_format(date_create($batch->submission_date), 'd/m/Y');

        if ($batch->patient_status == 1)
            $this->patient_status = "Berpencen";
        else 
            $this->patient_status = "Tidak Berpencen";

        // $this->patient_status = strtoupper($batch->patient_status);

        if ($batch->tariff == 3)
            $this->payor = "MINDEF";
        else
            $this->payor = "JPA/JHEV";

        // get order for each item
        $orders = Order::join('order_items', 'orders.id', '=', 'order_items.order_id')
            ->join('items', 'items.id', '=', 'order_items.myob_product_id')
            ->join('patients', 'patients.id', '=', 'orders.patient_id')
            ->join('cards', 'cards.id', '=', 'patients.card_id')
            ->join('tariffs', 'tariffs.id', '=', 'patients.tariff_id')
            ->where('orders.batch_id', '=', $this->batch_id)
            ->whereNull('order_items.deleted_at')
            ->orderBy('orders.id', 'ASC');
        
        $orders = $orders->select(
            'orders.id',
            'orders.do_number',
            'patients.full_name',
            'patients.identification',
            'cards.army_pension',
            'cards.type',
            'tariffs.name',
            'orders.dispense_date',
            'items.brand_name',
            'order_items.quantity',
            'orders.total_amount',
            'order_items.price',
        )->get();

        // add order into collection
        $collection = [];
        $num = 1;

        foreach ($orders as $k => $v) {
            $collection[$k]['ORDER ID'] = $v->id;
            $collection[$k]['NO'] = $num++;
            $collection[$k]['DO NUMBER'] = $v->do_number;
            $collection[$k]['PATIENT NAME'] = strtoupper($v->full_name);
            $collection[$k]['NRIC'] = $v->identification;
            $collection[$k]['PENSIONER NUMBER'] = $v->army_pension;
            $collection[$k]['PATIENT STATUS'] = $v->type;
            $collection[$k]['AGENCY'] = strtoupper($v->name);
            $collection[$k]['QUOTATION DATE'] = date_format(date_create($v->dispense_date), 'd/m/Y');
            $collection[$k]['ITEM'] = strtoupper($v->brand_name);
            $collection[$k]['QUANTITY'] = number_format($v->quantity, 0);
            $collection[$k]['TOTAL AMOUNT (RM)'] = number_format($v->total_amount, 2);

            $this->grand_total += $v->price;
        }

        return collect($collection);
    }

    public function headings(): array {
        return [
            [
                'ORDER ID',
                'NO',
                'DO NUMBER',
                'PATIENT NAME',
                'NRIC',
                'PENSIONER NUMBER',
                'PATIENT STATUS',
                'AGENCY',
                'QUOTATION DATE',
                'ITEM',
                'QUANTITY',
                'TOTAL AMOUNT (RM)'
            ],
            [
                'ORDER ID',
                'NO',
                'DO NUMBER',
                'PATIENT NAME',
                'NRIC',
                'PENSIONER NUMBER',
                'PATIENT STATUS',
                'AGENCY',
                'QUOTATION DATE',
                'ITEM',
                'QUANTITY',
                'TOTAL AMOUNT (RM)'
            ],
            [
                'ORDER ID',
                'NO',
                'DO NUMBER',
                'PATIENT NAME',
                'NRIC',
                'PENSIONER NUMBER',
                'PATIENT STATUS',
                'AGENCY',
                'QUOTATION DATE',
                'ITEM',
                'QUANTITY',
                'TOTAL AMOUNT (RM)'
            ],
            [
                'ORDER ID',
                'NO',
                'DO NUMBER',
                'PATIENT NAME',
                'NRIC',
                'PENSIONER NUMBER',
                'PATIENT STATUS',
                'AGENCY',
                'QUOTATION DATE',
                'ITEM',
                'QUANTITY',
                'TOTAL AMOUNT (RM)'
            ],
            [
                'ORDER ID',
                'NO',
                'DO NUMBER',
                'PATIENT NAME',
                'NRIC',
                'PENSIONER NUMBER',
                'PATIENT STATUS',
                'AGENCY',
                'QUOTATION DATE',
                'ITEM',
                'QUANTITY',
                'TOTAL AMOUNT (RM)'
            ],
            [
                'ORDER ID',
                'NO',
                'DO NUMBER',
                'PATIENT NAME',
                'NRIC',
                'PENSIONER NUMBER',
                'PATIENT STATUS',
                'AGENCY',
                'QUOTATION DATE',
                'ITEM',
                'QUANTITY',
                'TOTAL AMOUNT (RM)'
            ],
            [
                'ORDER ID',
                'NO',
                'DO NUMBER',
                'PATIENT NAME',
                'NRIC',
                'PENSIONER NUMBER',
                'PATIENT STATUS',
                'AGENCY',
                'QUOTATION DATE',
                'ITEM',
                'QUANTITY',
                'TOTAL AMOUNT (RM)'
            ],
            [
                'ORDER ID',
                'NO',
                'DO NUMBER',
                'PATIENT NAME',
                'NRIC',
                'PENSIONER NUMBER',
                'PATIENT STATUS',
                'AGENCY',
                'QUOTATION DATE',
                'ITEM',
                'QUANTITY',
                'TOTAL AMOUNT (RM)'
            ]
        ];
    }

    public function styles(Worksheet $sheet) {
        $right = array(
            'alignment' => array(
                'horizontal' => Alignment::HORIZONTAL_RIGHT,
                'vertical' => Alignment::VERTICAL_CENTER,
            )
        );

        $center = array(
            'alignment' => array(
                'horizontal' => Alignment::HORIZONTAL_CENTER,
                'vertical' => Alignment::VERTICAL_CENTER,
            )
        );

        for ($i = 1; $i < 8; $i++) {
            $sheet->setCellValue('A' .$i, '');
            $sheet->setCellValue('B' .$i, '');
            $sheet->setCellValue('C' .$i, '');
            $sheet->setCellValue('D' .$i, '');
            $sheet->setCellValue('E' .$i, '');
            $sheet->setCellValue('F' .$i, '');
            $sheet->setCellValue('G' .$i, '');
            $sheet->setCellValue('H' .$i, '');
            $sheet->setCellValue('I' .$i, '');
            $sheet->setCellValue('J' .$i, '');
            $sheet->setCellValue('K' .$i, '');
            $sheet->setCellValue('L' .$i, '');

            $sheet->mergeCells('B' . $i . ':D' . $i);
            $sheet->mergeCells('E' . $i . ':L' . $i);

            $sheet->getStyle('B' . $i)->getFont()->setBold(true);
            $sheet->getStyle('B' . $i)->applyFromArray($right);
        }

        $sheet->getStyle('A8:L8')->getFont()->setBold(true);

        $sheet->setCellValue('B1', 'COMPANY NAME :');
        $sheet->setCellValue('B2', 'BATCH NUMBER :');
        $sheet->setCellValue('B3', 'BATCH PERSON :');
        $sheet->setCellValue('B4', 'SUBMISSION DATE :');
        $sheet->setCellValue('B5', 'PAYOR :');
        $sheet->setCellValue('B6', 'PATIENT STATUS :');

        $sheet->setCellValue('E1', 'RASUMI MEDIPHARMA SDN BHD (727958-A)');
        $sheet->setCellValue('E2', $this->batch_no);
        $sheet->setCellValue('E3', $this->batch_person);
        $sheet->setCellValue('E4', $this->submission_date);
        $sheet->setCellValue('E5', $this->payor);
        $sheet->setCellValue('E6', $this->patient_status);

        $last_row = $sheet->getHighestRow();
        $current_row = 9;
        $start_row = 9;
        $end_row = 9;
        $num = 1;
        $current_id = $sheet->getCell('A'.$current_row)->getValue();

        for ($current_row = 9; $current_row <= $last_row; $current_row++) {
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

        $lastRow = $sheet->getHighestRow() + 1;

        $sheet->mergeCells('B' . $lastRow . ':K' . $lastRow);
        $sheet->setCellValue('B' . $lastRow, 'GRAND TOTAL :');
        $sheet->setCellValue('L' . $lastRow, number_format($this->grand_total, 2));
        $sheet->getStyle('B' . $lastRow . ':L' . $lastRow)->getFont()->setBold(true);
        $sheet->getStyle('B' . $lastRow)->applyFromArray($right);
        
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
        $sheet->mergeCells('L' .$start_row. ':L' .$end_row);

        $sheet->setCellValue('B'.$start_row, $num); 
    }

    public function columnFormats(): array {
        return [
            'L' => NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1,
        ];
    }
}