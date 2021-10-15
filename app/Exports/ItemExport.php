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

class ItemExport implements FromCollection, WithHeadings, WithStyles, WithColumnFormatting
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
            $price = 0;
            foreach($item->order_items AS $orderItem) {
                if ($orderItem->order) {
                    $quantity += $orderItem->quantity;
                    $price += $orderItem->price;
                }
            }
            $data[] = [
                'NO' => $no,
                'DATE' => $this->date,
                'ITEM CODE' => $item->item_code,
                'ITEM NAME' => $item->brand_name,
                'QUANTITY USED' => $quantity,
                'TOTAL PRICE (RM)' => $price
            ];
            $no++;
        }
        return collect($data);
    }

    public function headings(): array
    {
        return [
            'NO',
            'DATE',
            'ITEM CODE',
            'ITEM NAME',
            'QUANTITY USED',
            'TOTAL PRICE (RM)',
        ];
    }

    public function styles(Worksheet $sheet)
    {
        $sheet->getStyle('A1:F1')->getFont()->setBold(true);
    }

    public function columnFormats(): array
    {
        return [
             'F' => NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1
        ];
    }
}