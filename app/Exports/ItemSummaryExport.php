<?php

namespace App\Exports;

use App\Models\Item;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\WithColumnFormatting;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Style\Alignment;

class ItemSummaryExport implements WithColumnFormatting, WithHeadings, WithStyles, FromCollection
{
    use Exportable;

    private $dateStart;
    private $dateEnd;
    private $itemId;
    private $itemName;

    public function __construct($dateStart, $dateEnd, $itemId, $itemName)
    {
        $this->dateStart = $dateStart;
        $this->dateEnd = $dateEnd;
        $this->itemId = $itemId;
        $this->itemName = $itemName;
    }

    public function collection()
    {
        $results = DB::table('orders as a')
            ->join('order_items as b', 'b.order_id', '=', 'a.id')
            ->join('patients as c', 'c.id', '=', 'a.patient_id')
            ->selectRaw('a.dispense_date, a.do_number, a.dispensing_method, c.full_name, SUM(b.quantity) as quantity, SUM(b.price) as amount')
            ->where('b.myob_product_id', $this->itemId)
            ->whereDate('a.dispense_date', '>=', $this->dateStart)
            ->whereDate('a.dispense_date', '<=', $this->dateEnd)
            ->whereIn('a.status_id', [3,4,5])
            ->whereNull('a.deleted_at')
            ->whereNull('a.return_timestamp')
            ->whereNull('b.deleted_at')
            ->orderBy('a.dispense_date', 'DESC')
            ->groupby('a.id')
            ->get();

        $records = [];
        $count = 0;

        if (count($results) > 0) {
            foreach ($results as $k => $v) {
                $records[$k]['NO'] = ++$count;
                $records[$k]['DISPENSE DATE'] = $v->dispense_date;
                $records[$k]['DO NUMBER'] = $v->do_number;
                $records[$k]['DISPENSING METHOD'] = $v->dispensing_method;
                $records[$k]['CUSTOMER NAME'] = $v->full_name;
                $records[$k]['QUANTITY'] = $v->quantity;
                $records[$k]['AMOUNT (RM)'] = $v->amount;
            }
        }

        return collect($records);
    }

    public function headings(): array
    {
        return [
            [
                'NO',
                'DISPENSE DATE',
                'DO NUMBER',
                'DISPENSING METHOD',
                'CUSTOMER NAME',
                'QUANTITY',
                'AMOUNT (RM)'
            ],
            [
                'NO',
                'DISPENSE DATE',
                'DO NUMBER',
                'DISPENSING METHOD',
                'CUSTOMER NAME',
                'QUANTITY',
                'AMOUNT (RM)'
            ],
            [
                'NO',
                'DISPENSE DATE',
                'DO NUMBER',
                'DISPENSING METHOD',
                'CUSTOMER NAME',
                'QUANTITY',
                'AMOUNT (RM)'
            ],
            [
                'NO',
                'DISPENSE DATE',
                'DO NUMBER',
                'DISPENSING METHOD',
                'CUSTOMER NAME',
                'QUANTITY',
                'AMOUNT (RM)'
            ],
            [
                'NO',
                'DISPENSE DATE',
                'DO NUMBER',
                'DISPENSING METHOD',
                'CUSTOMER NAME',
                'QUANTITY',
                'AMOUNT (RM)'
            ],
            [
                'NO',
                'DISPENSE DATE',
                'DO NUMBER',
                'DISPENSING METHOD',
                'CUSTOMER NAME',
                'QUANTITY',
                'AMOUNT (RM)'
            ]
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

        $sheet->getStyle('A6:G6')->getFont()->setBold(true);

        for ($i = 1; $i < 6; $i++) {
            for ($j = 'A'; $j < 'H'; $j++) {
                $sheet->setCellValue($j.$i, '');
            }
        }

        $sheet->setCellValue('A2', 'Start Date :');
        $sheet->setCellValue('A3', 'End Date :');
        $sheet->setCellValue('A4', 'Item Name :');
        $sheet->setCellValue('D2', $this->dateStart);
        $sheet->setCellValue('D3', $this->dateEnd);
        $sheet->setCellValue('D4', strtoupper($this->itemName));

        $sheet->mergeCells('A2:C2');
        $sheet->mergeCells('A3:C3');
        $sheet->mergeCells('A4:C4');

        $sheet->mergeCells('D2:G2');
        $sheet->mergeCells('D3:G3');
        $sheet->mergeCells('D4:G4');

        $sheet->getStyle('A2:A4')->getFont()->setBold(true);
        $sheet->getStyle('A2:A4')->applyFromArray($right);

        $sheet->getColumnDimension('A')->setAutoSize(true);
        $sheet->getColumnDimension('B')->setAutoSize(true);
        $sheet->getColumnDimension('C')->setAutoSize(true);
        $sheet->getColumnDimension('D')->setAutoSize(true);
        $sheet->getColumnDimension('E')->setAutoSize(true);
        $sheet->getColumnDimension('F')->setAutoSize(true);
        $sheet->getColumnDimension('G')->setAutoSize(true);
    }

    public function columnFormats(): array
    {
        return [
            'G' => NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1
        ];
    }
}