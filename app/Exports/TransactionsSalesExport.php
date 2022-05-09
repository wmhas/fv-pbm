<?php 

namespace App\Exports;

use App\Models\Order;
use Maatwebsite\Excel\Concerns\WithColumnFormatting;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Style\Alignment;

class TransactionsSalesExport implements FromCollection, WithHeadings, WithStyles, WithColumnFormatting
{

    use Exportable;
    private $startDate = false;
    private $endDate = false;
    private $grand_total;

    public function __construct($startDate, $endDate, $page = null)
    {
        $this->startDate = $startDate;
        $this->endDate = $endDate;
        $this->page = $page;
        $this->grand_total = 0;
    }

    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        $order = Order::join("patients","patients.id","=","orders.patient_id")
            ->join("cards","cards.id","=","patients.card_id")
            ->join("prescriptions","prescriptions.order_id","=","orders.id")
            ->join("order_items","order_items.order_id","=","orders.id")
            ->join("items","items.id","=","order_items.myob_product_id")
            ->join("states","states.id","=","patients.state_id")
            ->join("statuses","statuses.id","=","orders.status_id")
            ->whereIn('orders.status_id', [3, 4, 5]);

        $order = Order::whereIn('orders.status_id', [3, 4, 5]);
        
        if ($this->startDate && $this->endDate){
            $order = $order->whereDate('orders.dispense_date', '>=', $this->startDate)
                    ->whereDate('orders.dispense_date', '<=', $this->endDate);
        }

        $order->orderBy('orders.dispense_date', 'DESC');

        if ($this->page) {
            $order = $order->paginate(10, ['*'], 'page', $this->page);
        } else {
            $order = $order->get();
        }

        $orders = [];

        $num = 1;

        $this->grand_total = 0;

        if (count($order)>0){
            foreach ($order as $k => $v) {

                $address = "";
                
                if (!empty($v->patient)) {
                    if (!empty($v->patient->address_1))
                        $address .= $v->patient->address_1;
                    if (!empty($v->patient->address_2))
                        $address .= " " .$v->patient->address_2;
                    if (!empty($v->patient->address_3))
                        $address .= " " .$v->patient->address_3;
                    if (!empty($v->patient->postcode))
                        $address .= " " .$v->patient->postcode;
                    if (!empty($v->patient->city))
                        $address .= " " .$v->patient->city;
                    if (!empty($v->patient->state))
                        if (!empty($v->patient->state->name))
                            $address .= " " .$v->patient->state->name;
                }
                
                if (!empty($v->id)) {
                    $orders[$k]['ORDER ID'] = $v->id;
                } else {
                    $orders[$k]['ORDER ID'] = "";
                }
                
                $orders[$k]['NO'] = $num;

                if (!empty($v->dispense_date)) {
                    $orders[$k]['DISPENSING DATE'] = $v->dispense_date;
                } else {
                    $orders[$k]['DISPENSING DATE'] = "";
                }
                
                if (!empty($v->do_number)) {
                    $orders[$k]['DO NUMBER'] = $v->do_number;
                } else {
                    $orders[$k]['DO NUMBER'] = "";
                }

                if (!empty($v->batch)) {
                    if (!empty($v->batch->batch_no)) {
                        $orders[$k]['BATCH NUMBER'] = $v->batch->batch_no;
                    } else {
                        $orders[$k]['BATCH NUMBER'] = "";
                    }
                } else {
                    $orders[$k]['BATCH NUMBER'] = "";
                }
                
                if (!empty($v->patient)) {
                    if (!empty($v->patient->identification)) {
                        $orders[$k]['IC'] = $v->patient->identification;
                    } else {
                        $orders[$k]['IC'] = "";
                    }    
                } else {
                    $orders[$k]['IC'] = "";
                }

                if (!empty($v->patient)) {
                    if (!empty($v->patient->card)) {
                        if (!empty($v->patient->card->army_pension)) {
                            $orders[$k]['ARMY NO'] = $v->patient->card->army_pension;
                        } else {
                            $orders[$k]['ARMY NO'] = "";
                        }
                    } else {
                        $orders[$k]['ARMY NO'] = "";
                    }    
                } else {
                    $orders[$k]['ARMY NO'] = "";
                }
                
                if (!empty($v->patient)) {
                    if (!empty($v->patient->full_name)) {
                        $orders[$k]['FULLANME'] = $v->patient->full_name;
                    } else {
                        $orders[$k]['FULLANME'] = "";
                    }
                } else {
                    $orders[$k]['FULLANME'] = "";
                }
                
                $orders[$k]['ADDRESS']=trim($address);

                if (!empty($v->prescription)) {
                    if (!empty($v->prescription->rx_number)) {
                        $orders[$k]['RX NUMBER'] = $v->prescription->rx_number;
                    } else {
                        $orders[$k]['RX NUMBER'] = "";
                    }
                } else {
                    $orders[$k]['RX NUMBER'] = "";
                }
                
                
                if (!empty($v->patient)) {
                    if (!empty($v->patient->tariff)) {
                        if (!empty($v->patient->tariff->name)) {
                            $orders[$k]['PANEL'] = $v->patient->tariff->name;
                        } else {
                            $orders[$k]['PANEL'] = "";
                        }
                    } else {
                        $orders[$k]['PANEL'] = "";
                    }
                } else {
                    $orders[$k]['PANEL'] = "";
                }

                if (!empty($v->prescription)) {
                    if (!empty($v->prescription->clinic)) {
                        if (!empty($v->prescription->clinic->name)) {
                            $orders[$k]['CLINIC'] = $v->prescription->clinic->name;
                        } else {
                            $orders[$k]['CLINIC'] = "";
                        }
                    } else {
                        $orders[$k]['CLINIC'] = "";
                    }
                } else {
                    $orders[$k]['CLINIC'] = "";
                }

                if (!empty($v->dispensing_method)) {
                    $orders[$k]['DISPENSING METHOD'] = $v->dispensing_method;
                } else {
                    $orders[$k]['DISPENSING METHOD'] = "";
                }

                if (!empty($v->total_amount)) {
                    $orders[$k]['TOTAL AMOUNT'] = $v->total_amount;
                } else {
                    $orders[$k]['TOTAL AMOUNT'] = "";
                }

                if (!empty($v->patient)) {
                    if (!empty($v->patient->card)) {
                        if (!empty($v->patient->card->type)) {
                            $orders[$k]['STATUS'] = $v->patient->card->type;
                        } else {
                            $orders[$k]['STATUS'] = "";
                        }
                    } else {
                        $orders[$k]['STATUS'] = "";
                    }
                } else {
                    $orders[$k]['STATUS'] = "";
                }

                if (!empty($v->status)){
                    if (!empty($v->status->name)){
                        $orders[$k]['ORDER STATUS'] = $v->status->name;
                    } else {
                        $orders[$k]['ORDER STATUS'] = "";
                    }
                } else {
                    $orders[$k]['ORDER STATUS'] = "";
                }

                $num++;

                $this->grand_total += $v->total_amount;
            }
        }

        return collect($orders);
    }

    public function headings(): array
    {
        return [
            [
                'ORDER ID',
                'NO',
                'DISPENSING DATE',
                'DO NUMBER',
                'BATCH NUMBER',
                'IC',
                'ARMY NO',
                'FULLNAME',
                'ADDRESS',
                'RX NUMBER',
                'PANEL',
                'CLINIC',
                'DISPENSING METHOD',
                'TOTAL AMOUNT',
                'STATUS',
                'ORDER STATUS',
                'REMARKS',
            ],
            [
                'ORDER ID',
                'NO',
                'DISPENSING DATE',
                'DO NUMBER',
                'BATCH NUMBER',
                'IC',
                'ARMY NO',
                'FULLNAME',
                'ADDRESS',
                'RX NUMBER',
                'PANEL',
                'CLINIC',
                'DISPENSING METHOD',
                'TOTAL AMOUNT',
                'STATUS',
                'ORDER STATUS',
                'REMARKS',
            ],
            [
                'ORDER ID',
                'NO',
                'DISPENSING DATE',
                'DO NUMBER',
                'BATCH NUMBER',
                'IC',
                'ARMY NO',
                'FULLNAME',
                'ADDRESS',
                'RX NUMBER',
                'PANEL',
                'CLINIC',
                'DISPENSING METHOD',
                'TOTAL AMOUNT',
                'STATUS',
                'ORDER STATUS',
                'REMARKS',
            ],
            [
                'ORDER ID',
                'NO',
                'DISPENSING DATE',
                'DO NUMBER',
                'BATCH NUMBER',
                'IC',
                'ARMY NO',
                'FULLNAME',
                'ADDRESS',
                'RX NUMBER',
                'PANEL',
                'CLINIC',
                'DISPENSING METHOD',
                'TOTAL AMOUNT',
                'STATUS',
                'ORDER STATUS',
                'REMARKS',
            ],
            [
                'ORDER ID',
                'NO',
                'DISPENSING DATE',
                'DO NUMBER',
                'BATCH NUMBER',
                'IC',
                'ARMY NO',
                'FULLNAME',
                'ADDRESS',
                'RX NUMBER',
                'PANEL',
                'CLINIC',
                'DISPENSING METHOD',
                'TOTAL AMOUNT (RM)',
                'STATUS',
                'ORDER STATUS',
                'REMARKS',
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

        $sheet->getStyle('A5:Q5')->getFont()->setBold(true);
        $sheet->getColumnDimension('A')->setVisible(false);

        for ($i = 1; $i < 5; $i++) {
            for ($j = 'A'; $j < 'R'; $j++) {
                $sheet->setCellValue($j.$i, '');
            }
        }

        $sheet->setCellValue('B2', 'Start Date :');
        $sheet->setCellValue('B3', 'End Date :');
        $sheet->setCellValue('D2', $this->startDate);
        $sheet->setCellValue('D3', $this->endDate);

        $sheet->mergeCells('B2:C2');
        $sheet->mergeCells('B3:C3');

        $sheet->getStyle('B2:B3')->getFont()->setBold(true);
        $sheet->getStyle('B2:B3')->applyFromArray($right);

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
        $sheet->getColumnDimension('L')->setAutoSize(true);
        $sheet->getColumnDimension('M')->setAutoSize(true);
        $sheet->getColumnDimension('N')->setAutoSize(true);
        $sheet->getColumnDimension('O')->setAutoSize(true);
        $sheet->getColumnDimension('P')->setAutoSize(true);
        $sheet->getColumnDimension('Q')->setAutoSize(true);

        $lastRow = $sheet->getHighestRow() + 1;

        $sheet->getStyle('B' . $lastRow . ':N' . $lastRow)->getFont()->setBold(true);
        $sheet->getStyle('B' . $lastRow)->applyFromArray($right);
        $sheet->mergeCells('B' . $lastRow . ':M' . $lastRow);
        $sheet->setCellValue('B' . $lastRow, 'GRAND TOTAL :');
        $sheet->setCellValue('N' . $lastRow, $this->grand_total);
    }

    public function columnFormats(): array
    {
        return [
            'N' => NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1,
        ];
    }
}