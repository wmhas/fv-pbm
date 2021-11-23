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
                'PATIENT NAME' => $refill->patient->full_name,
                'PATIENT NRIC' => $refill->patient->identification,
                'PRESCRIPTION NO' => $refill->prescription->rx_number,
                'PRESCRIPTION START DATE' => $refill->prescription->rx_start,
                'PRESCRIPTION END DATE' => $refill->prescription->rx_end,
                'NEXT SUPPLY DATE' => date("d/m/Y", strtotime($refill->prescription->next_supply_date)),
                'CLINIC' => $refill->prescription->clinic->name,
                'DISPENSING METHOD' => $refill->dispensing_method,
                'RESUBMISSION' => $refill->rx_interval === 3 ? 'Complete' : 'Incomplete',
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
            'PATIENT NAME',
            'PATIENT NRIC',
            'PRESCRIPTION NO',
            'PRESCRIPTION START DATE',
            'PRESCRIPTION END DATE',
            'NEXT SUPPLY DATE',
            'CLINIC',
            'DISPENSING METHOD',
            'RESUBMISSION',
        ];
    }

    public function styles(Worksheet $sheet): void
    {
        $sheet->getStyle('A1:K1')->getFont()->setBold(true);
    }

    public function columnFormats(): array
    {
        return [
            'B' => NumberFormat::FORMAT_TEXT,
            'E' => NumberFormat::FORMAT_TEXT,
        ];
    }
}