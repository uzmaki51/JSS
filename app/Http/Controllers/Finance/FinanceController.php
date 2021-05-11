<?php

namespace App\Http\Controllers\Finance;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Util;
use App\Models\Decision\DecisionReport;

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
        return view('finance.books', [
            'start_year' => '2020',
			'start_month' => '11',
			'year' => '2020',
			'month' => '11',
        ]);
    }

	public function getBookList(Request $request)
	{
		$params = $request->all();
		$userid = Auth::user()->id;

		$decideTbl = new DecisionReport();
		$reportList = $decideTbl->getForDatatable($params);

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