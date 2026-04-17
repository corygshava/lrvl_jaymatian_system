<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Http\Request;
use App\Http\Controllers\CashReportController;

class SaveDailyReport extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:save-daily-report';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Saves a daily report to the database';

    /**
     * Execute the console command.
     */
    public function handle(){
		$ccr = app(CashReportController::class);
        $res = $ccr->save_me(new Request(),true);
		// unset($res['runlog']);
		$tolog = [
			'runtime data' => $res,
			'added_by' => 0,
		];
        $ccr->log_payload($tolog,'daily_reports',true);
		echo json_encode($res,JSON_PRETTY_PRINT);
		// dd($res);
    }
}
