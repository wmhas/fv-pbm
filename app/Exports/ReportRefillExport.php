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

class ReportRefillExport implements FromCollection, WithHeadings, WithStyles, WithColumnFormatting
{
    use Exportable;

    private $data;

    public function __construct($data)
    {
        $this->data = $data;
    }

    public function collection(): Collection
    {
        $data = $this->data;
        $refills = [];
        $no = 1;
        foreach ($data AS $refill) {
            $refills[] = [
                'NO' => $no,
                'DO NUMBER' => $refill->do_number,
                'PATIENT' => $refill->patient->full_name,
                'PRESCRIPTION' => $refill->prescription->rx_number,
                'NEXT SUPPLY DATE' => date("d/m/Y", strtotime($refill->prescription->next_supply_date)),
                'RESUBMISSION' => $refill->rx_interval === 3 ? 'Complete' : '',
            ];
            $no++;
        }
        return collect($refills);
    }

    public function headings(): array
    {
        return [
            'NO',
            'DO NUMBER',
            'PATIENT',
            'PRESCRIPTION',
            'NEXT SUPPLY DATE',
            'RESUBMISSION',
        ];
    }

    public function styles(Worksheet $sheet): void
    {
        $sheet->getStyle('A1:F1')->getFont()->setBold(true);
    }

    public function columnFormats(): array
    {
        return [
            'B' => NumberFormat::FORMAT_TEXT,
            'D' => NumberFormat::FORMAT_TEXT,
        ];
    }
}