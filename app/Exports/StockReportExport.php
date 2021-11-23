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
use Maatwebsite\Excel\Concerns\WithStrictNullComparison;
use Carbon\Carbon;

class StockReportExport implements FromCollection, WithHeadings, WithStyles, WithStrictNullComparison
{

    use Exportable;

    public function __construct($data)
    {
        $this->data = $data;
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
        $sheet->getStyle('A1:J1')->getFont()->setBold(true);
        $sheet->getStyle('A2:J2')->getFont()->setBold(true);

        $sheet->mergeCells('A1:A2');
        $sheet->mergeCells('B1:B2');
        $sheet->mergeCells('G1:G2');
        $sheet->mergeCells('H1:H2');
        $sheet->mergeCells('I1:I2');
        $sheet->mergeCells('J1:J2');

        $sheet->mergeCells('C1:D1');
        $sheet->mergeCells('E1:F1');
    }
}