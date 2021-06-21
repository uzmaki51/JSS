<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
*/

Route::get('/', 'Auth\AuthController@loginPage');
Route::get('home', 'HomeController@index');

Route::group(['prefix' => 'org'], function() {
	Route::get('userPrivilege', 	['uses'=>'OrgManage\OrgmanageController@userPrivilege']);
	Route::get('userInfoListView', 	['uses'=>'OrgManage\OrgmanageController@userInfoListView']);
	Route::post('memberList', 	['uses'=>'OrgManage\OrgmanageController@getUserInfoList']);
	Route::get('memberadd',		['uses'=>'OrgManage\OrgmanageController@addMemberinfo']);
	Route::post('memberadder',	['uses'=>'OrgManage\OrgmanageController@addMember']);
	Route::post('upload',		['uses'=>'OrgManage\OrgmanageController@upload']);
	Route::post('memberupdate',	['uses'=>'OrgManage\OrgmanageController@updateMember']);
	Route::post('memberInfo/delete',	['uses'=>'OrgManage\OrgmanageController@deleteMember']);
	Route::get('privilege',		['uses'=>'OrgManage\OrgmanageController@addPrivilege']);
	Route::post('storePrivilege',		['uses'=>'OrgManage\OrgmanageController@storePrivilege']);
	Route::get('system/backup', ['uses'=>'OrgManage\BackupController@index']);
	Route::get('system/settings', ['uses'=>'OrgManage\SettingsController@index']);
	Route::post('system/updateSettings',	['uses'=>'OrgManage\OrgmanageController@updateSettings']);
});

Route::group(['prefix' => 'decision'], function()
{
	Route::get('/', ['uses'=>'Decision\DecisionController@index']);
	Route::get('receivedReport', ['uses'=>'Decision\DecisionController@receivedReport']);
	Route::get('draftReport', ['uses'=>'Decision\DecisionController@draftReport']);
	Route::get('redirect', ['uses'=>'Decision\DecisionController@redirect']);
	Route::post('report/submit', ['uses'=>'Decision\DecisionController@reportSubmit']);
	Route::post('getACList', ['uses'=>'Decision\DecisionController@getACList']);
});

Route::group(['prefix' => 'ajax'], function() {
	Route::post('decide/receive',   ['uses'=>'Decision\DecisionController@ajaxGetReceive']);
	Route::post('report/decide',    ['uses'=>'Decision\DecisionController@ajaxReportDecide']);
	Route::post('report/detail',    ['uses'=>'Decision\DecisionController@ajaxReportDetail']);
	Route::post('report/getData',    ['uses'=>'Decision\DecisionController@ajaxReportData']);
	Route::post('report/fileupload',    ['uses'=>'Decision\DecisionController@ajaxReportFile']);
	
	Route::post('object', ['uses'=>'Decision\DecisionController@ajaxObject']);
	Route::post('report/attachment/delete', ['uses'=>'Decision\DecisionController@ajaxDeleteReportAttach']);
	Route::post('report/delete', ['uses'=>'Decision\DecisionController@ajaxDelete']);
	Route::post('decide/draft',   ['uses'=>'Decision\DecisionController@ajaxGetDraft']);
	Route::post('ship/voyList',    ['uses'=>'Decision\DecisionController@ajaxGetVoyList']);
	Route::post('profit/list',    ['uses'=>'Decision\DecisionController@ajaxProfitList']);
	Route::post('getDepartment',    ['uses'=>'Decision\DecisionController@ajaxGetDepartment']);
	Route::post('getDynamicData', ['uses' => 'Dynamic\DynamicController@ajaxGetDynamicData']);
	Route::post('setDynamicData', ['uses' => 'Dynamic\DynamicController@ajaxSetDynamicData']);
	Route::post('shipMember/listAll', ['uses' => 'ShipManage\ShipMemberController@ajaxGetWholeList']);
	Route::post('shipMember/search', ['uses' => 'ShipManage\ShipMemberController@ajaxSearchMember']);
	Route::post('shipMember/searchAll', ['uses' => 'ShipManage\ShipMemberController@ajaxSearchMemberAll']);
	Route::post('shipMember/searchWageById', ['uses' => 'ShipManage\ShipMemberController@ajaxSearchWageById']);
	Route::post('shipMember/cert/list', ['uses' => 'ShipManage\ShipMemberController@ajaxShipMemberCertList']);
	Route::post('shipMember/wage/list', ['uses' => 'ShipManage\ShipMemberController@ajaxSearchMemberWithWage']);
	Route::post('shipMember/wage/send', ['uses' => 'ShipManage\ShipMemberController@ajaxSearchMemberWithSendWage']);
	Route::post('shipMember/wage/shiplist', ['uses' => 'ShipManage\ShipMemberController@ajaxGetShipWageList']);
	Route::post('shipMember/wage/memberlist', ['uses' => 'ShipManage\ShipMemberController@ajaxGetShipMemberList']);
	Route::post('shipMember/wage/initCalc', ['uses' => 'ShipManage\WageController@initWageCalcInfo']);
	Route::post('shipMember/wage/initSend', ['uses' => 'ShipManage\WageController@initWageSendInfo']);
	Route::get('shipMember/autocomplete', ['uses' => 'ShipManage\ShipMemberController@autocomplete']);
	Route::get('shipMember/autocompleteAll', ['uses' => 'ShipManage\ShipMemberController@autocompleteAll']);

	Route::post('shipManage/cert/list', ['uses'=>'ShipManage\ShipRegController@ajaxShipCertList']);
	Route::post('shipManage/cert/add', ['uses'=>'ShipManage\ShipRegController@ajaxCertAdd']);
	Route::post('shipManage/cert/delete', ['uses'=>'ShipManage\ShipRegController@ajaxCertItemDelete']);
	Route::post('shipManage/shipCert/delete', ['uses'=>'ShipManage\ShipRegController@ajaxShipCertDelete']);
	Route::post('shipManage/dynamic/search', ['uses'	=> 'ShipManage\ShipRegController@ajaxDynamicSearch']);
	Route::post('shipManage/equipment/list', ['uses'	=> 'ShipManage\ShipRegController@ajaxEquipmentList']);
	Route::post('shipManage/equipment/delete', ['uses'	=> 'ShipManage\ShipRegController@ajaxShipEquipDelete']);
	Route::post('shipManage/equipment/require/list', ['uses'	=> 'ShipManage\ShipRegController@ajaxReqEquipmentList']);
	Route::post('shipManage/equipment/require/delete', ['uses'	=> 'ShipManage\ShipRegController@ajaxShipReqEquipDelete']);
	Route::post('shipManage/equipment/require/type/list', ['uses'	=> 'ShipManage\ShipRegController@ajaxShipReqEquipTypeList']);
	Route::post('shipManage/equipment/require/type/delete', ['uses'	=> 'ShipManage\ShipRegController@ajaxShipReqEquipTypeDelete']);	

	Route::post('shipManage/evaluation/list', ['uses'	=> 'ShipManage\ShipRegController@ajaxEvaluation']);	
	Route::post('shipManage/evaluation/else', ['uses'	=> 'ShipManage\ShipRegController@ajaxEvaluationElse']);	

	//Business
	Route::post('business/cp/list', ['uses'	=> 'Business\BusinessController@ajaxCPList']);
	Route::post('business/cp/delete', ['uses'	=> 'Business\BusinessController@ajaxVoyDelete']);
	Route::post('business/contract/info', ['uses'=>'Business\BusinessController@ajaxContractInfo']);
	Route::post('business/voyNo/validate', ['uses'=>'Business\BusinessController@ajaxVoyNoValid']);
	Route::post('business/cargo/list', ['uses'	=> 'Business\BusinessController@ajaxCargoList']);
	Route::post('business/cargo/delete', ['uses'	=> 'Business\BusinessController@ajaxCargoDelete']);
	Route::post('business/port/delete', ['uses'	=> 'Business\BusinessController@ajaxPortDelete']);
	Route::post('business/dynamic', ['uses'	=> 'Business\BusinessController@ajaxDynamic']);
	Route::post('business/dynamic/list', ['uses'	=> 'Business\BusinessController@ajaxDynamicList']);
	Route::post('business/dynrecord/delete', ['uses'	=> 'Business\BusinessController@ajaxDeleteDynrecord']);
	Route::post('business/voy/list', ['uses'	=> 'Business\BusinessController@ajaxVoyAllList']);
	Route::post('business/dynamic/search', ['uses'	=> 'Business\BusinessController@ajaxDynamicSearch']);
	Route::post('business/dynamic/multiSearch', ['uses'	=> 'Business\BusinessController@ajaxDynamicMultiSearch']);
	Route::post('business/ctm/list', ['uses'	=> 'Business\BusinessController@ajaxCtm']);	
	Route::post('business/ctm/delete', ['uses'	=> 'Business\BusinessController@ajaxCtmDelete']);
	Route::post('shipmanage/ctm/total', ['uses'=>'ShipManage\ShipRegController@ajaxCtmTotal']);
	Route::post('shipmanage/ctm/debit', ['uses'=>'ShipManage\ShipRegController@ajaxCtmDebit']);
	Route::post('shipmanage/ctm/debits', ['uses'=>'ShipManage\ShipRegController@ajaxCtmDebits']);
	Route::post('business/voySettle/index', ['uses'=>'Business\BusinessController@ajaxVoySettleIndex']);
	Route::post('business/voySettle/elseInfo/delete', ['uses'=>'Business\BusinessController@ajaxVoySettleDelete']);
	Route::post('system/backup/list', ['uses'=>'OrgManage\BackupController@getList']);
	Route::post('system/backup/add', ['uses'=>'OrgManage\BackupController@add']);
	Route::post('system/backup/backup', ['uses'=>'OrgManage\BackupController@backup']);
	Route::post('system/backup/restore', ['uses'=>'OrgManage\BackupController@restore']);
	Route::post('finance/books/list', ['uses'=>'Finance\FinanceController@getBookList']);
	Route::post('finance/books/init', ['uses'=>'Finance\FinanceController@initBookList']);
	Route::post('finance/waters/list', ['uses'=>'Finance\FinanceController@getWaterList']);
	Route::post('finance/accounts/report/list', ['uses'=>'Finance\FinanceController@getReportList']);
	Route::post('finance/accounts/analysis/list', ['uses'=>'Finance\FinanceController@getAnalysisList']);
	Route::post('finance/accounts/info/list', ['uses'=>'Finance\FinanceController@getPersonalInfoList']);
	Route::post('finance/accounts/setting/list', ['uses'=>'Finance\FinanceController@getSettingList']);
	Route::post('operation/listByShipForPast', ['uses'=>'Operation\OperationController@ajaxIncomeExportListByShipForPast']);		// dailyAverage
	Route::post('operation/listByShip', ['uses'=>'Operation\OperationController@ajaxIncomeExportListByShip']);		// incomeExpense -> Table, Graph
	Route::post('operation/listBySOA', ['uses'=>'Operation\OperationController@ajaxListBySOA']);					// incomeExpense -> SOA
	Route::post('operation/listByAll', ['uses'=>'Operation\OperationController@ajaxListByAll']);					// incomeExpenseAll -> Table
});

Route::group(['prefix' => 'business'], function() {
	Route::get('contract', ['uses' => 'Business\BusinessController@contract']);
	Route::post('voyContractRegister', ['uses'	=> 'Business\BusinessController@saveVoyContract']);
	Route::post('saveCargoList', ['uses'	=> 'Business\BusinessController@saveCargoList']);	
	Route::post('savePortList', ['uses'	=> 'Business\BusinessController@savePortList']);	
	Route::post('tcContractRegister', ['uses'	=> 'Business\BusinessController@saveTcContract']);
	Route::post('nonContractRegister', ['uses'	=> 'Business\BusinessController@saveNonContract']);
	Route::get('dynRecord', ['uses' => 'Business\BusinessController@dynRecord']);
	Route::post('saveDynamic', ['uses'=>'Business\BusinessController@saveDynamic']);
	Route::get('settleMent', ['uses' => 'Business\BusinessController@settleMent']);
	Route::post('saveVoySettle', ['uses' => 'Business\BusinessController@saveVoySettle']);
	Route::get('ctm', ['uses'	=> 'Business\BusinessController@ctm']);
	Route::post('saveCtmList', ['uses'	=> 'Business\BusinessController@saveCtmList']);
	Route::get('dailyAverageCost', ['uses'=>'Business\BusinessController@dailyAverageCost']);
	Route::post('updateCostInfo', ['uses' => 'Business\BusinessController@updateCostInfo']);
});

Route::group(['prefix' => 'finance'], function() {
	Route::get('books', ['uses' => 'Finance\FinanceController@books']);
	Route::get('accounts', ['uses' => 'Finance\FinanceController@accounts']);
	Route::post('books/save', ['uses'=>'Finance\FinanceController@saveBookList']);
	Route::post('accounts/info/save', ['uses'=>'Finance\FinanceController@savePersonalInfoList']);
	Route::post('accounts/setting/save', ['uses'=>'Finance\FinanceController@saveSettingList']);
});

Route::group(['prefix' => 'operation'], function() {
	Route::get('incomeExpense', ['uses' => 'Operation\OperationController@incomeExpense']);
	Route::get('incomeAllExpense', ['uses' => 'Operation\OperationController@incomeAllExpense']);
});
Route::group(['prefix' => 'shipManage'], function() {
	Route::get('/', ['uses'=>'ShipManage\ShipRegController@index']);
	Route::get('shipinfo', ['uses'=>'ShipManage\ShipRegController@loadShipGeneralInfos']);
	Route::get('registerShipData', ['uses'=>'ShipManage\ShipRegController@registerShipData']);
	Route::post('deleteShipData', ['uses'=>'ShipManage\ShipRegController@deleteShipData']);
	Route::post('loadShipTypePage', ['uses'=>'ShipManage\ShipRegController@loadShipTypePage']);
	Route::post('shipDataTabPage', ['uses'=>'ShipManage\ShipRegController@shipDataTabPage']);
	Route::post('loadShipTypeData', ['uses'=>'ShipManage\ShipRegController@loadShipTypeData']);
	Route::post('loadShipTypeModifyPage', ['uses'=>'ShipManage\ShipRegController@loadShipTypeModifyPage']);
	Route::post('saveShipData', ['uses'=>'ShipManage\ShipRegController@saveShipData']);
	Route::post('saveShipGeneralData', ['uses'=>'ShipManage\ShipRegController@saveShipGeneralData']);
	Route::post('saveShipHullData', ['uses'=>'ShipManage\ShipRegController@saveShipHullData']);
	Route::post('saveShipMachineryData', ['uses'=>'ShipManage\ShipRegController@saveShipMachineryData']);
	Route::post('saveShipRemarksData', ['uses'=>'ShipManage\ShipRegController@saveShipRemarksData']);
	Route::post('saveShipSafetyData', ['uses'=>'ShipManage\ShipRegController@saveShipSafetyData']);
	Route::post('deleteShipSafetyData', ['uses'=>'ShipManage\ShipRegController@deleteShipSafetyData']);
	Route::get('dynamicList', ['uses'=>'ShipManage\ShipRegController@dynamicList']);
	Route::get('ctm/analytics', ['uses'=>'ShipManage\ShipRegController@ctmAnalytics']);

	Route::get('voy/evaluation', ['uses'=>'ShipManage\ShipRegController@voyEvaluation']);

	Route::get('shipCertList', ['uses'=>'ShipManage\ShipRegController@shipCertList']);
	Route::post('shipCertList', ['uses'=>'ShipManage\ShipRegController@saveShipCertList']);
	Route::post('shipCertType', ['uses'=>'ShipManage\ShipRegController@saveShipCertType']);
	Route::post('getShipCertInfo', ['uses'=>'ShipManage\ShipRegController@getShipCertInfo']);
	Route::post('updateCertInfo', ['uses'=>'ShipManage\ShipRegController@updateCertInfo']);
	Route::post('deleteShipCert', ['uses'=>'ShipManage\ShipRegController@deleteShipCert']);
	Route::get('shipCertManage', ['uses'=>'ShipManage\ShipRegController@shipCertManage']);
	Route::post('getCertType', ['uses'=>'ShipManage\ShipRegController@getCertType']);
	Route::post('updateCertType', ['uses'=>'ShipManage\ShipRegController@updateCertType']);
	Route::post('deleteShipCertType', ['uses'=>'ShipManage\ShipRegController@deleteShipCertType']);
	Route::get('shipEquipmentManage', ['uses'=>'ShipManage\ShipRegController@shipEquipmentManage']);
	Route::get('fuelManage', ['uses'=>'ShipManage\ShipRegController@fuelManage']);
	Route::post('fuelSave', ['uses'=>'ShipManage\ShipRegController@fuelSave']);
	Route::get('equipment', ['uses'=>'ShipManage\ShipRegController@shipEquipmentManage']);
	Route::post('shipEquipmentList', ['uses'=>'ShipManage\ShipRegController@saveShipEquipList']);

	Route::post('shipReqEquipmentList', ['uses'=>'ShipManage\ShipRegController@saveShipReqEquipList']);
	Route::post('saveShipReqEquipmentType', ['uses'=>'ShipManage\ShipRegController@saveShipReqEquipType']);
    Route::get('exportShipInfo', ['uses'=>'ShipManage\ShipRegController@exportShipInfo']);
});

Route::group(['prefix' => 'shipMember'], function() {
	Route::get('/', ['uses' => 'ShipManage\ShipMemberController@index']);
	Route::get('shipMember', ['uses' => 'ShipManage\ShipMemberController@loadShipMembers']);
	Route::get('registerShipMember', ['uses' => 'ShipManage\ShipMemberController@registerShipMember']);
	Route::post('showShipMemberDataTab', ['uses' => 'ShipManage\ShipMemberController@showShipMemberDataTab']);
	Route::post('updateMemberInfo', ['uses' => 'ShipManage\ShipMemberController@updateMemberInfo']);
	Route::post('updateMemberMainInfo', ['uses' => 'ShipManage\ShipMemberController@updateMemberMainInfo']);
	Route::post('updateMemberMainData', ['uses' => 'ShipManage\ShipMemberController@updateMemberMainData']);
	Route::post('updateMemberCapacityData', ['uses' => 'ShipManage\ShipMemberController@updateMemberCapacityData']);
	Route::post('updateMemberTrainingData', ['uses' => 'ShipManage\ShipMemberController@updateMemberTrainingData']);
	Route::post('registerMemberExamingData', ['uses' => 'ShipManage\ShipMemberController@registerMemberExamingData']);
	Route::post('deleteShipMember', ['uses'=>'ShipManage\ShipMemberController@deleteShipMember']);
	Route::get('totalShipMember', ['uses' => 'ShipManage\ShipMemberController@totalShipMember']);
	Route::get('memberCertList', ['uses' => 'ShipManage\ShipMemberController@memberCertList']);
	Route::get('integretedMemberExaming', ['uses' => 'ShipManage\ShipMemberController@integretedMemberExaming']);
	Route::get('wagesCalc', ['uses' => 'ShipManage\WageController@index']);
	Route::get('wagesSend', ['uses' => 'ShipManage\WageController@send']);
	Route::get('wagesList', ['uses' => 'ShipManage\WageController@wagelist']);
	Route::post('updateWageCalcInfo', ['uses' => 'ShipManage\WageController@updateWageCalcInfo']);
	Route::post('updateWageSendInfo', ['uses' => 'ShipManage\WageController@updateWageSendInfo']);
});

Route::get('/home/translate/{local}', 'HomeController@translatePage');
Route::any('/home/resetPassword', 'HomeController@resetPassword');
Route::get('fileDownload', 'HomeController@file_download');
Route::get('FileUpload', 'HomeController@file_upload');
Route::get('FitleUpload', 'HomeController@file_upload');
Route::get('GotoWelcome', 'HomeController@gotoWelcome');
Route::controllers([
	'auth' => 'Auth\AuthController',
	'password' => 'Auth\PasswordController',
]);

