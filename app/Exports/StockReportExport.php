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

class StockReportExport implements FromCollection, WithHeadings, WithStyles
{

    use Exportable;
    private $startDate = false;
    private $endDate = false;

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
                $orders[$k]['Item Code'] = $v->item_code;
                $orders[$k]['Item Name'] = $v->brand_name;
                $orders[$k]['Available']= (strlen($v->counter)==0) ? 0 : $v->counter;
                $orders[$k]['Committed']= $committed_counter[$k];
                $orders[$k]['Available']= (strlen($v->courier)==0) ? 0 : $v->courier;
                $orders[$k]['Committed']= $committed_courier[$k];
                $orders[$k]['Staff']= (strlen($v->staff)==0) ? 0 : $v->staff;
                $orders[$k]['Store']= (strlen($v->store)==0) ? 0 : $v->store;
                $orders[$k]['On Hand']= $v->counter + $v->courier + $v->staff + $v->store ;
                $orders[$k]['Quantity Used']= $committed_counter[$k] + $committed_courier[$k];
            }
        }
        
        return collect($orders);
    }

    public function headings(): array
    {
        return [
            'Item Code',
            'Item Name',
            'Available',
            'Committed',
            'Available',
            'Committed',
            'Staff',
            'Store',
            'On Hand',
            'Quantity Used',
        ];
    }

    public function styles(Worksheet $sheet)
    {
        $sheet->getStyle('A1:J1')->getFont()->setBold(true);
    }
}