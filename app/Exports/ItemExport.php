<?php 

namespace App\Exports;

use App\Models\Order;
use App\Models\Item;
use App\Models\OrderItem;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\Exportable;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class ItemExport implements FromCollection, WithHeadings, WithStyles
{

    use Exportable;

    private $items;
    private $date;

    public function __construct($items, $date)
    {
        $this->items = $items;
        $this->date = $date;
    }

    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        $data = [];
        $no = 1;
        foreach ($this->items AS $item) {
            $quantity = 0;
            If (!empty($item->used_stock())) {
                if($item->used_stock()->quantity < 0) {
                    $quantity = substr($item->used_stock()->quantity, 1);
                } else {
                    $quantity = $item->used_stock()->quantity;
                }
            }
            $data[] = [
                'NO' => $no,
                'ITEM CODE' => $item->item_code,
                'ITEM NAME' => $item->brand_name,
                'QUANTITY USED' => $quantity,
                'TOTAL PRICE' => $item->total_price($this->date)
            ];
            $no++;
        }
        return collect($data);
    }

    public function headings(): array
    {
        return [
            'NO',
            'ITEM CODE',
            'ITEM NAME',
            'QUANTITY USED',
            'TOTAL PRICE',
        ];
    }

    public function styles(Worksheet $sheet)
    {
        $sheet->getStyle('A1:E1')->getFont()->setBold(true);
    }

}