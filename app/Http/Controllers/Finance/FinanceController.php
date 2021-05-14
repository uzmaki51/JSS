<?php

namespace App\Http\Controllers\Finance;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Util;
use App\Models\Decision\DecisionReport;
use App\Models\Finance\BooksList;
use App\Models\Finance\ReportSave;

use App\User;
use Illuminate\Contracts\Logging\Log;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\DB;
use Symfony\Component\HttpKernel\Profiler\Profiler;

class FinanceController extends Controller
{
    public function __construct() {
        $this->middleware('auth');
    }

    public function books(Request $request)
    {
		$year = $request->get('year');
        $month = $request->get('month');

        return view('finance.books', [
            'start_year' => '2020',
			'start_month' => '11',
			'year' => $year,
			'month' => $month,
			'book_no' => 3,
        ]);
    }

	public function saveBookList(Request $request)
	{
		$year = $request->get('select-year');
        $month = $request->get('select-month');
		$rate = $request->get('keep_rate');
		$book_no = $request->get('keep-list-bookno');
		$datetime = $request->get('keep-list-datetime');
		$pay_type = $request->get('pay_type');
		$account_type = $request->get('account_type');

		$report_ids = $request->get('report_id');
		$report_contents = $request->get('report_remark');
		$report_booknos = $request->get('book_no');
		$report_credits = $request->get('credit');
		$report_debits = $request->get('debit');
		$report_rates = $request->get('rate');


		//var_dump($report_booknos);
		//die;
		//return $report_debits;

		$report_list_record = BooksList::where('year', $year)->where('month', $month)->first();
        if (is_null($report_list_record)) {
            $report_list_record = new BooksList();
        }
        $report_list_record['year'] = $year;
        $report_list_record['month'] = $month;
		$report_list_record->save();

		ReportSave::where('year', $year)->where('month', $month)->delete();
		foreach($report_ids as $index => $item) {
			$report_save_record = new ReportSave();
			$report_original_record = DecisionReport::where('id', $item)->first();
			$report_save_record['orig_id'] = $item;
			$report_save_record['flowid'] = $report_original_record->flowid;
			$report_save_record['type'] = $report_original_record->type;
			$report_save_record['profit_type'] = $report_original_record->profit_type;
			$report_save_record['shipNo'] = $report_original_record->shipNo;
			$report_save_record['voyNo'] = $report_original_record->voyNo;
			
			
			if ($report_booknos[$index] != '')
			{
				if ($report_original_record->flowid == "Credit") {
					$report_save_record['amount'] = ($report_credits[$index] == '') ? null : str_replace(",","",$report_credits[$index]);
				} else {
					$report_save_record['amount'] = ($report_debits[$index] == '') ? null : str_replace(",","",$report_debits[$index]);
				}
			}
			else
			{
				$report_save_record['amount'] = $report_original_record->amount;
			}


			$report_save_record['currency'] = $report_original_record->currency;
			$report_save_record['creator'] = $report_original_record->creator;
			$report_save_record['recvUser'] = $report_original_record->recvUser;
			$report_save_record['content'] = $report_contents[$index];
			$report_save_record['rate'] = ($report_rates[$index] == '') ? null : $report_rates[$index];
			$report_save_record['book_no'] = ($report_booknos[$index] == "") ? null :str_replace("J-", "", $report_booknos[$index]);
			$report_save_record['attachment'] = $report_original_record->attachment;
			$report_save_record['year'] = $year;
			$report_save_record['month'] = $month;
			$report_save_record['create_time'] = $report_original_record->create_at;

			$report_save_record->save();
		}

		return redirect('finance/books?'.'year='.$year.'&month='.$month);
	}

	public function getBookList(Request $request)
	{
		$params = $request->all();
		$decideTbl = new DecisionReport();
		$reportList = $decideTbl->getForBookDatatable($params);

		return response()->json($reportList);
	}

	public function getList(Request $request)
	{
		$backupTbl = new BackupDB();
		$result = $backupTbl->getForDatatable($request->all());

		return response()->json($result);
	}

	public function add(Request $request)
	{
		$backupTbl = new BackupDB();
		$result = $backupTbl->addTransaction($request->all());

		return response()->json($result);
	}

	public function backup(Request $request)
	{
		$backupTbl = new BackupDB();
		$result = $backupTbl->runBackup($request->all());

		return response()->json($result);
	}

    public function restore(Request $request)
	{
		$backupTbl = new BackupDB();
		$result = $backupTbl->runRestore($request->all());

		return response()->json($result);
	}
}