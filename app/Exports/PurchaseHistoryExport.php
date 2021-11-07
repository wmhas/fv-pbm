<?php 

namespace App\Exports;

use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\WithColumnFormatting;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use Carbon\Carbon;

class PurchaseHistoryExport implements FromCollection, WithHeadings, WithStyles, WithColumnFormatting
{
    use Exportable;

    private $data;

    public function __construct($data)
    {
        $this->data = $data;
    }

    /**
    * @return Collection
    */
    public function collection(): Collection
    {
        $data = $this->data;
        $purchases = [];
        $no = 1;
        foreach ($data AS $purchase) {
            $purchases[] = [
                'NO' => $no,
                'ITEM NAME' => $purchase->item->brand_name,
                'PO NUMBER' => $purchase->po_number,
                'QUANTITY' => $purchase->quantity . ' ' . $purchase->purchase_uom,
                'PRICE/UOM (RM)' => $purchase->item->purchase_price,
                'TOTAL (RM)' => $purchase->purchase_price,
                'DATE CREATED' => Carbon::parse($purchase->created_at)->format('d/m/Y '),
                'SALES PERSON' => $purchase->salespersons->name,
            ];
            $no++;
        }
        return collect($purchases);
    }

    public function headings(): array
    {
        return [
            'NO',
            'ITEM NAME',
            'PO NUMBER',
            'QUANTITY',
            'PRICE/UOM (RM)',
            'TOTAL (RM)',
            'DATE CREATED',
            'SALES PERSON',
        ];
    }

    public function styles(Worksheet $sheet): void
    {
        $sheet->getStyle('A1:H1')->getFont()->setBold(true);
    }

    public function columnFormats(): array
    {
        return [
            'C' => NumberFormat::FORMAT_TEXT,
            'E' => NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1,
            'F' => NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1,
        ];
    }
}