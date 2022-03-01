<?php

namespace App\Jobs;

use App\Download;
use App\Exports\TransactionsExport;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Maatwebsite\Excel\Facades\Excel;

class ExportTransactionJob implements ShouldQueue
{
    use Dispatchable,   InteractsWithQueue, Queueable, SerializesModels;

    private $startDate;
    private $endDate;
    private $user_id;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($startDate, $endDate, $user_id)
    {
        $this->startDate = $startDate;
        $this->endDate = $endDate;
        $this->user_id = $user_id;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $transaction = new TransactionsExport($this->startDate, $this->endDate);
        $filename = 'Sales Report Details ('. $this->startDate . " to " . $this->endDate .').xlsx';

        $download = new Download();
        $download->filename = $filename;
        $download->status = 'Generating File...';
        $download->user_id = $this->user_id;
        $download->save();

        if ($transaction->collection()->count() > 0) {
            Excel::store($transaction, $filename);

            $download->status = 'File Generated.';
            $download->save();

        } else {
            
            $download->status = 'Failed!';
            $download->save();
        }

        
    }
}
