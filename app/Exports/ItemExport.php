<?php 

namespace App\Exports;

use Carbon\Carbon;
use Maatwebsite\Excel\Concerns\WithColumnFormatting;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class ItemExport implements FromCollection, WithHeadings, WithStyles, WithColumnFormatting
{

    use Exportable;

    private $items;

    public function __construct($items)
    {
        $this->items = $items;
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
            $date = '-';
            foreach($item->order_items AS $orderItem) {
                if ($orderItem->order) {
                    $quantity += $orderItem->quantity;
                    $price += $orderItem->price;
                    $date = (new Carbon($orderItem->order->updated_at))->translatedFormat('d-m-Y');
                }
            }
            $data[] = [
                'NO' => $no,
                'DATE' => $date,
                'ITEM CODE' => $item->item_code,
                'ITEM NAME' => $item->brand_name,
                'QUANTITY USED' => ($quantity === 0) ? '0' : $quantity,
                'TOTAL PRICE (RM)' => ($price === 0) ? '0.00' : $price
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