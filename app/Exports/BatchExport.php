<?php 

namespace App\Exports;

use App\Models\Order;
use App\Models\Item;
use App\Models\OrderItem;
use App\Models\BatchOrder;
use Maatwebsite\Excel\Concerns\WithColumnFormatting;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\Exportable;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class BatchExport implements FromCollection, WithHeadings, WithStyles, WithColumnFormatting
{

    use Exportable;

    private $batch_id;

    public function __construct($batch_id) 
    {
        $this->batch_id = $batch_id;
    }

    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        $order = BatchOrder::where("batch_id", $this->batch_id)->get();

        $orders = [];

        $num = 1;

        if (count($order)>0){

            foreach ($order as $kv => $v) {

                $oi = OrderItem::with("items")->where("order_id",$v->order->id)->get();
                
                if (count($oi)>0) {
                    foreach ($oi as $k => $voi) {
                        if ($k==0){ 
                            $orders[$k]['No'] = $num;
                            $orders[$k]['DO Number']=$v->order->do_number;
                            $orders[$k]['RX Number']=$v->order->prescription->rx_number;
                            $orders[$k]['Patient Name']=$v->order->patient->full_name;
                            $orders[$k]['Patient IC']=$v->order->patient->identification;
                            $orders[$k]['Patient Pensioner No']=$v->order->patient->card->army_pension;
                            $orders[$k]['Agency']=$v->order->patient->tariff->name;
                            $orders[$k]['Quotation Date']=$v->order->created_at;
                            $orders[$k]['Item']=$voi->items->brand_name;
                            $orders[$k]['Qty']=$voi->quantity;
                            $orders[$k]['Total Price (RM)'] = $v->order->total_amount;
                            $orders[$k]['Status'] = $v->order->patient->card->type;
                            $orders[$k]['Batch Person'] = (!empty($batch->batchperson_id)) ? $v->batchperson->name : "";
                        } else {
                            $orders[$k]['No'] = "";
                            $orders[$k]['DO Number']="";
                            $orders[$k]['RX Number']="";
                            $orders[$k]['Patient Name']="";
                            $orders[$k]['Patient IC']="";
                            $orders[$k]['Patient Pensioner No']="";
                            $orders[$k]['Agency']="";
                            $orders[$k]['Quotation Date']="";
                            $orders[$k]['Item']=$voi->items->brand_name;
                            $orders[$k]['Qty']=$voi->quantity;
                            $orders[$k]['Total Price (RM)'] = "";
                            $orders[$k]['Status'] = "";
                            $orders[$k]['Batch Person'] = "";
                        }
                    }

                    if (!empty($orders[$k]['NO'])){
                        $num+=1;
                    }
                } else {
                    $orders[$kv]['No'] = $num;
                    $orders[$kv]['DO Number']=$v->order->do_number;
                    $orders[$kv]['RX Number']=$v->order->prescription->rx_number;
                    $orders[$kv]['Patient Name']=$v->order->patient->full_name;
                    $orders[$kv]['Patient IC']=$v->order->patient->identification;
                    $orders[$kv]['Patient Pensioner No']=$v->order->patient->card->army_pension;
                    $orders[$kv]['Agency']=$v->order->patient->tariff->name;
                    $orders[$kv]['Quotation Date']=$v->order->created_at;
                    $orders[$kv]['Item']=$voi->items->brand_name;
                    $orders[$kv]['Qty']=$voi->quantity;
                    $orders[$kv]['Total Price (RM)'] = $v->order->total_amount;
                    $orders[$kv]['Status'] = $v->order->patient->card->type;
                    $orders[$kv]['Batch Person'] = (!empty($batch->batchperson_id)) ? $v->batchperson->name : "";
                }
            }

        }
        
        return collect($orders);
    }

    public function headings(): array
    {
        return [
            'NO',
            'DO Number',
            'RX Number',
            'Patient Name',
            'Patient IC',
            'Patient Pensioner No',
            'Agency',
            'Quotation Date',
            'Item',
            'Qty',
            'Total Price (RM)',
            'Status',
            'Batch Person'
        ];
    }

    public function styles(Worksheet $sheet)
    {
        $sheet->getStyle('A1:M1')->getFont()->setBold(true);
    }

    public function columnFormats(): array
    {
        return [
            'K' => NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1,
            'L' => NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1,
        ];
    }
}