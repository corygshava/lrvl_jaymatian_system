<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Http\Request;
use App\Http\Controllers\CashReportController;

class SaveMonthlyReport extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:save-monthly-report';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Saves the Monthly report for the system';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $ccr = app(CashReportController::class);
        $res = $ccr->save_me_monthly(new Request(),true);
		// unset($res['runlog']);
		$tolog = [
			'runtime data' => $res,
			'added_by' => 0,
		];
        $ccr->log_payload($tolog,'monthly_reports',true);
		echo json_encode($res,JSON_PRETTY_PRINT);
		// dd($res);
    }
}
