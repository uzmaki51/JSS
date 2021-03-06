<?php

namespace App\Http\Controllers\Orgmanage;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Util;
use App\Models\Member\Career;
use App\Models\Operations\BackupDB;

use App\User;
use Illuminate\Contracts\Logging\Log;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\DB;
use Symfony\Component\HttpKernel\Profiler\Profiler;

class BackupController extends Controller
{
    public function __construct() {
        $this->middleware('auth');
    }

    public function index(Request $request)
    {
        return view('orgmanage.backup', [
            'title' => ''
        ]);
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