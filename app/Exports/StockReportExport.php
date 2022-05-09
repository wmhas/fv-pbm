<?php 

namespace App\Exports;

use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use Maatwebsite\Excel\Concerns\WithStrictNullComparison;
use PhpOffice\PhpSpreadsheet\Style\Alignment;

class StockReportExport implements FromCollection, WithHeadings, WithStyles, WithStrictNullComparison
{

    use Exportable;

    private $dateStart;
    private $dateEnd;

    public function __construct($data, $dateStart, $dateEnd)
    {
        $this->data = $data;
        $this->dateStart = $dateStart;
        $this->dateEnd = $dateEnd;
    }

    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        $data = $this->data["items"];
        $committed_courier = $this->data["committed_courier"];
        $committed_counter = $this->data["committed_counter"];
        $orders = [];

        if (count($data)>0){
            foreach ($data as $k => $v) {
                $orders[$k]['Item Code'] = (string)$v->item_code;
                $orders[$k]['Item Name'] = (string)$v->brand_name;
                $orders[$k]['Available']= (string)(strlen($v->counter)==0) ? "0" : $v->counter;
                $orders[$k]['Committed']= (string)$committed_counter[$k];
                $orders[$k]['Available.']= (string)(strlen($v->courier)==0) ? "0" : $v->courier;
                $orders[$k]['Committed.']= $committed_courier[$k];
                $orders[$k]['Staff']= (string)(strlen($v->staff)==0) ? "0" : $v->staff;
                $orders[$k]['Store']= (string)(strlen($v->store)==0) ? "0" : $v->store;
                $orders[$k]['On Hand']= (string)($v->counter + $v->courier + $v->staff + $v->store) ;
                $orders[$k]['Quantity Used']= (string)($committed_counter[$k] + $committed_courier[$k]);
            }
        }
        
        return collect($orders);
    }

    public function headings(): array
    {
        return [
            [
                '',
                '',
                '',
                '',
                '',
                '',
                '',
                '',
                '',
                '',
            ],
            [
                '',
                '',
                '',
                '',
                '',
                '',
                '',
                '',
                '',
                '',
            ],
            [
                '',
                '',
                '',
                '',
                '',
                '',
                '',
                '',
                '',
                '',
            ],
            [
                '',
                '',
                '',
                '',
                '',
                '',
                '',
                '',
                '',
                '',
            ],
            [
                'Item Code',
                'Item Name',
                'Counter',
                '',
                'Courier',
                '',
                'Staff',
                'Store',
                'On Hand',
                'Quantity Used',
            ],
            [
                '',
                '',
                'Available',
                'Committed',
                'Available',
                'Committed',
                '',
                '',
                '',
                '',
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

        $center = array(
            'alignment' => array(
                'horizontal' => Alignment::HORIZONTAL_CENTER,
                'vertical' => Alignment::VERTICAL_CENTER,
            )
        );

        $sheet->getColumnDimension('A')->setAutoSize(true);
        $sheet->getColumnDimension('B')->setAutoSize(true);
        $sheet->getColumnDimension('C')->setAutoSize(true);
        $sheet->getColumnDimension('D')->setAutoSize(true);
        $sheet->getColumnDimension('E')->setAutoSize(true);
        $sheet->getColumnDimension('F')->setAutoSize(true);
        $sheet->getColumnDimension('G')->setAutoSize(true);
        $sheet->getColumnDimension('H')->setAutoSize(true);
        $sheet->getColumnDimension('I')->setAutoSize(true);
        $sheet->getColumnDimension('J')->setAutoSize(true);

        $sheet->getStyle('A5:J5')->getFont()->setBold(true);
        $sheet->getStyle('A6:J6')->getFont()->setBold(true);

        $sheet->mergeCells('A5:A6');
        $sheet->mergeCells('B5:B6');
        $sheet->mergeCells('G5:G6');
        $sheet->mergeCells('H5:H6');
        $sheet->mergeCells('I5:I6');
        $sheet->mergeCells('J5:J6');

        $sheet->getStyle('A5:J5')->applyFromArray($center);
        $sheet->getStyle('A6:J6')->applyFromArray($center);

        $sheet->mergeCells('C5:D5');
        $sheet->mergeCells('E5:F5');

        $sheet->mergeCells('A2:B2');
        $sheet->mergeCells('C2:J2');
        $sheet->mergeCells('A3:B3');
        $sheet->mergeCells('C3:J3');
        $sheet->getStyle('A2:A3')->applyFromArray($right);
        $sheet->getStyle('A2:J2')->getFont()->setBold(true);
        $sheet->getStyle('A3:J3')->getFont()->setBold(true);

        $sheet->setCellValue('A2', 'Date Start:');
        $sheet->setCellValue('A3', 'Date End:');
        $sheet->setCellValue('C2', $this->dateStart);
        $sheet->setCellValue('C3', $this->dateEnd);
    }
}