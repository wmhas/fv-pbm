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
use PhpOffice\PhpSpreadsheet\Style\Alignment;

class ReportRefillExport implements FromCollection, WithHeadings, WithStyles, WithColumnFormatting
{
    use Exportable;

    private $data;
    private $dateStart;
    private $dateEnd;

    public function __construct($data, $dateStart, $dateEnd)
    {
        $this->data = $data;
        $this->dateStart = $dateStart;
        $this->dateEnd = $dateEnd;
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
                'NEXT SUPPLY DATE' => date("Y-m-d", strtotime($refill->prescription->next_supply_date)),
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
            [
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
            ],
            [
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
            ],
            [
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
            ],
            [
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
            ],
            [
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
            ],
        ];
    }

    public function styles(Worksheet $sheet): void
    {
        $right = array(
            'alignment' => array(
                'horizontal' => Alignment::HORIZONTAL_RIGHT,
                'vertical' => Alignment::VERTICAL_CENTER,
            )
        );

        for ($i = 1; $i < 5; $i++) {
            for ($j = 'A'; $j < 'L'; $j++) {
                $sheet->setCellValue($j.$i, '');
            }
        }

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
        $sheet->getColumnDimension('K')->setAutoSize(true);

        $sheet->getStyle('A2:K2')->getFont()->setBold(true);
        $sheet->getStyle('A3:K3')->getFont()->setBold(true);
        $sheet->getStyle('A5:K5')->getFont()->setBold(true);

        $sheet->mergeCells('A2:C2');
        $sheet->mergeCells('D2:K2');
        $sheet->mergeCells('A3:C3');
        $sheet->mergeCells('D3:K3');

        $sheet->setCellValue('A2', 'Date Start :');
        $sheet->setCellValue('A3', 'Date End :');
        $sheet->getStyle('A2:A3')->applyFromArray($right);
        $sheet->setCellValue('D2', $this->dateStart);
        $sheet->setCellValue('D3', $this->dateEnd);
    }

    public function columnFormats(): array
    {
        return [
            'B' => NumberFormat::FORMAT_TEXT,
            'E' => NumberFormat::FORMAT_TEXT,
        ];
    }
}