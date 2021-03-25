<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the controller to call when that URI is requested.
|
*/

Route::get('/', 'Auth\AuthController@loginPage');
Route::get('home', 'HomeController@index');
//Route::get('bankRate', 'HomeController@searchList');

//Organization Manage
Route::group(['prefix' => 'org'], function() {
	Route::get('quartermanage', ['uses'=>'OrgManage\OrgmanageController@unitManage']);
	Route::post('quarterregister', ['uses'=>'OrgManage\OrgmanageController@unitRegister']);
	Route::post('quarterdel',	['uses'=>'OrgManage\OrgmanageController@unitDelete']);
	Route::post('quarterupdate',	['uses'=>'OrgManage\OrgmanageController@unitUpdate']);
	Route::get('quarterload',	['uses'=>'OrgManage\OrgmanageController@load']);

	Route::get('userInfoListView', 	['uses'=>'OrgManage\OrgmanageController@userInfoListView']);
	Route::get('userPrivilege', 	['uses'=>'OrgManage\OrgmanageController@userPrivilege']);
	Route::post('memberList', 	['uses'=>'OrgManage\OrgmanageController@getUserInfoList']);
	Route::get('memberadd',		['uses'=>'OrgManage\OrgmanageController@addMemberinfo']);
	Route::post('memberadder',	['uses'=>'OrgManage\OrgmanageController@addMember']);
	Route::post('upload',		['uses'=>'OrgManage\OrgmanageController@upload']);
	Route::post('memberupdate',	['uses'=>'OrgManage\OrgmanageController@updateMember']);
	Route::post('updateMemberSchoolCarrer',	['uses'=>'OrgManage\OrgmanageController@updateMemberSchoolCarrer']);
	Route::post('deleteSchoolCarrer',	['uses'=>'OrgManage\OrgmanageController@deleteSchoolCarrer']);
	Route::post('updateMemberFamily',	['uses'=>'OrgManage\OrgmanageController@updateMemberFamily']);
	Route::post('deleteMemberFamily',	['uses'=>'OrgManage\OrgmanageController@deleteMemberFamily']);
	Route::post('updateRelationItem',	['uses'=>'OrgManage\OrgmanageController@updateRelationItem']);
	Route::post('deleteRelationItem',	['uses'=>'OrgManage\OrgmanageController@deleteRelationItem']);

	Route::get('postmanage', 		['uses'=>'OrgManage\OrgmanageController@showpostmanage']);
	Route::get('postmanage/save', 	['uses'=>'OrgManage\OrgmanageController@savepost']);
	Route::get('quartermanager',	['uses'=>'OrgManage\OrgmanageController@showquartermanager']);
	Route::get('quartermanagerload',['uses'=>'OrgManage\OrgmanageController@loadquartermanager']);
	Route::get('postUpdate', 		['uses'=>'OrgManage\OrgmanageController@updatepost']);
	Route::get('postDel', 			['uses'=>'OrgManage\OrgmanageController@delpost']);
	Route::get('postAdd', 			['uses'=>'OrgManage\OrgmanageController@addpost']);
	Route::get('managerUpdate', 	['uses'=>'OrgManage\OrgmanageController@updatequartermanager']);
	Route::get('managerDel', 		['uses'=>'OrgManage\OrgmanageController@delquartermanager']);

	// Add by Uzmaki
	Route::post('memberInfo/delete',	['uses'=>'OrgManage\OrgmanageController@deleteMember']);
	Route::get('privilege',		['uses'=>'OrgManage\OrgmanageController@addPrivilege']);
	Route::post('storePrivilege',		['uses'=>'OrgManage\OrgmanageController@storePrivilege']);


});

// Electronic Pay
Route::group(['prefix' => 'decision'], function()
{
	Route::get('/', ['uses'=>'Decision\DecisionController@index']);

	Route::get('receivedReport', ['uses'=>'Decision\DecisionController@receivedReport']);
	Route::get('draftReport', ['uses'=>'Decision\DecisionController@draftReport']);
	Route::get('redirect', ['uses'=>'Decision\DecisionController@redirect']);

	Route::post('report/submit', ['uses'=>'Decision\DecisionController@reportSubmit']);

	// Ajax
	Route::post('getACList', ['uses'=>'Decision\DecisionController@getACList']);
});

// Ajax
Route::group(['prefix'  => 'ajax'], function() {
	Route::post('decide/receive',   ['uses'=>'Decision\DecisionController@ajaxGetReceive']);
	Route::post('report/decide',    ['uses'=>'Decision\DecisionController@ajaxReportDecide']);
	Route::post('report/detail',    ['uses'=>'Decision\DecisionController@ajaxReportDetail']);
	Route::post('report/getData',    ['uses'=>'Decision\DecisionController@ajaxReportData']);
	Route::post('report/fileupload',    ['uses'=>'Decision\DecisionController@ajaxReportFile']);

	Route::post('decide/draft',   ['uses'=>'Decision\DecisionController@ajaxGetDraft']);

	Route::post('ship/voyList',    ['uses'=>'Decision\DecisionController@ajaxGetVoyList']);
	Route::post('profit/list',    ['uses'=>'Decision\DecisionController@ajaxProfitList']);
	Route::post('getDepartment',    ['uses'=>'Decision\DecisionController@ajaxGetDepartment']);
});

// Administrative Affairs
Route::group(['prefix' => 'business'], function() {
	Route::get('/', ['uses'=>'Business\BusinessController@index']);
	Route::get('newsTemaPage', ['uses'=>'Business\BusinessController@newsTemaPage']);
	Route::get('temaInfo',['uses'=>'Business\BusinessController@getNewsTemaInfo']);

	Route::get('mainplan', ['uses'=>'Business\BusinessController@mainPlan']);
	Route::post('planUpdate', ['uses'=>'Business\BusinessController@planUpdate']);
	Route::post('planAdd', ['uses'=>'Business\BusinessController@planAdd']);
	Route::post('planDelete', ['uses'=>'Business\BusinessController@planDelete']);

	Route::get('reportperson', ['uses'=>'Business\BusinessController@reportPerson']);
	Route::post('changeMangeTable', ['uses'=>'Business\BusinessController@changeMangeTable']);
	Route::post('reportPersonUpdate', ['uses'=>'Business\BusinessController@reportPersonUpdate']);
	Route::post('addSubTask', ['uses'=>'Business\BusinessController@addSubTask']);
	Route::post('deleteSubTask', ['uses'=>'Business\BusinessController@deleteSubTask']);
	Route::post('reportPersonSearch', ['uses'=>'Business\BusinessController@reportPersonSearch']);
	Route::post('reportPersonUpdateWeekList', ['uses'=>'Business\BusinessController@reportPersonUpdateWeekList']);
	Route::post('reportPersonUpdateMonthList', ['uses'=>'Business\BusinessController@reportPersonUpdateMonthList']);
	Route::post('reportPersonUpdateAllList', ['uses'=>'Business\BusinessController@reportPersonUpdateAllList']);
	Route::get('reportUnitWeek', ['uses'=>'Business\BusinessController@reportUnitWeek']);
	Route::post('reportUnitUpdateWeekList', ['uses'=>'Business\BusinessController@reportUnitUpdateWeekList']);
	Route::get('reportUnitMonth', ['uses'=>'Business\BusinessController@reportUnitMonth']);
	Route::post('reportUnitUpdateMonthList', ['uses'=>'Business\BusinessController@reportUnitUpdateMonthList']);
	Route::get('reportUnitWeekRead', ['uses'=>'Business\BusinessController@reportUnitWeekRead']);
	Route::post('reportUnitUpdateWeekReadList', ['uses'=>'Business\BusinessController@reportUnitUpdateWeekReadList']);
	Route::get('reportUnitMonthRead', ['uses'=>'Business\BusinessController@reportUnitMonthRead']);
	Route::post('reportUnitUpdateMonthReadList', ['uses'=>'Business\BusinessController@reportUnitUpdateMonthReadList']);
	Route::get('reportPerUnit', ['uses'=>'Business\BusinessController@reportPerUnit']);
	Route::post('reportPerUnitByWeek', ['uses'=>'Business\BusinessController@reportPerUnitByWeek']);
	Route::get('reportPerUnitMonth', ['uses'=>'Business\BusinessController@reportPerUnitMonth']);
	Route::post('reportPerUnitMonthList', ['uses'=>'Business\BusinessController@reportPerUnitMonthList']);
	Route::any('reportEnterpriseWeek', ['uses'=>'Business\BusinessController@reportEnterpriseWeek']);
	Route::post('reportEnterpriseUpdateWeekList', ['uses'=>'Business\BusinessController@reportEnterpriseUpdateWeekList']);
	Route::get('reportEnterpriseMonth', ['uses'=>'Business\BusinessController@reportEnterpriseMonth']);
	Route::post('reportEnterpriseUpdateMonthList', ['uses'=>'Business\BusinessController@reportEnterpriseUpdateMonthList']);
	Route::any('reportEnterpriseWeekRead', ['uses'=>'Business\BusinessController@reportEnterpriseWeekRead']);
	Route::get('reportEnterpriseMonthRead', ['uses'=>'Business\BusinessController@reportEnterpriseMonthRead']);
	Route::post('reportEnterpriseUpdateMonthReadList', ['uses'=>'Business\BusinessController@reportEnterpriseUpdateMonthReadList']);

	Route::get('recommendNews',['uses'=>'Business\BusinessController@recommendNews']);

	Route::get('showNewsDetail/{id}.htm',['uses'=>'Business\BusinessController@showNewsDetail']);
	Route::get('createNewsPage',['uses'=>'Business\BusinessController@createNewsPage']);
	Route::get('createNewsPage/{id}.htm',['uses'=>'Business\BusinessController@updateNewsPage']);
	Route::post('createNewsContent',['uses'=>'Business\BusinessController@createNewsContent']);
	Route::post('newsRecommend',['uses'=>'Business\BusinessController@newsRecommend']);
	Route::post('newsResponse',['uses'=>'Business\BusinessController@newsResponse']);

	Route::any('showTotalMemberList', ['uses' => 'Business\BusinessController@showTotalMemberList']);
	Route::get('personSchedule',['uses'=>'Business\BusinessController@personSchedule']);
	Route::post('updateSchedule',['uses'=>'Business\BusinessController@updateSchedule']);
	Route::any('getScheduleInfo',['uses'=>'Business\BusinessController@getScheduleInfo']);
	Route::post('deleteScheduleInfo',['uses'=>'Business\BusinessController@deleteScheduleInfo']);
	Route::get('checkPersonSchedule',['uses'=>'Business\BusinessController@checkPersonSchedule']);

	Route::get('entryandexit/{page_id?}', ['uses' => 'Business\BusinessController@EntryAndExit']);
	Route::post('timeUpdate', ['uses' => 'Business\BusinessController@timeUpdate']);
	Route::post('restUpdate', ['uses' => 'Business\BusinessController@restUpdate']);
	Route::post('restDelete', ['uses' => 'Business\BusinessController@restDelete']);
	Route::post('restAdd', ['uses' => 'Business\BusinessController@restAdd']);
	Route::get('mainplan', ['uses' => 'Business\BusinessController@mainPlan']);
	Route::post('planUpdate', ['uses' => 'Business\BusinessController@planUpdate']);
	Route::post('planAdd', ['uses' => 'Business\BusinessController@planAdd']);
	Route::post('planDelete', ['uses' => 'Business\BusinessController@planDelete']);
	Route::post('planSearch', ['uses' => 'Business\BusinessController@planSearch']);

	Route::get('personnelregister', ['uses' => 'Business\BusinessController@personnelRegister']);
	Route::any('setPersonAttend', ['uses' => 'Business\BusinessController@savePersonRegisterInfo']);

	Route::get('shipmemberregister', ['uses' => 'Business\BusinessController@shipMemberRegister']);
	Route::post('getAttendShipMemberListByDate', ['uses' => 'Business\BusinessController@getAttendShipMemberListByDate']);
	Route::post('registerShipMemberAttend', ['uses' => 'Business\BusinessController@registerShipMemberAttend']);
	Route::post('registerShipAllMember', ['uses' => 'Business\BusinessController@registerShipAllMember']);
	Route::get('shipAttendDayPage', ['uses' => 'Business\BusinessController@shipAttendDayPage']);

	Route::get('unitAttendPage', ['uses' => 'Business\BusinessController@unitAttendPage']);
	Route::get('unitAttendDayPage', ['uses' => 'Business\BusinessController@unitAttendDayPage']);
	Route::post('registerUnitMemberAttend', ['uses' => 'Business\BusinessController@registerUnitMemberAttend']);

	Route::get('unitAttendMonthShow', ['uses' => 'Business\BusinessController@unitAttendMonthShow']);
	Route::any('memberMonthAttend', ['uses' => 'Business\BusinessController@memberMonthAttend']);
	Route::any('shipMemberMonthAttend', ['uses' => 'Business\BusinessController@shipMemberMonthAttend']);
	Route::get('enterpriseDayAttend', ['uses' => 'Business\BusinessController@enterpriseDayAttend']);
	Route::get('enterpriseMonthAttend', ['uses' => 'Business\BusinessController@enterpriseMonthAttend']);
	Route::get('memberWeekAndMonthReport', ['uses'=>'Business\BusinessController@memberWeekAndMonthReport']);
	Route::post('memberWeekReport', ['uses'=>'Business\BusinessController@memberWeekReport']);
	Route::post('memberMonthReport', ['uses'=>'Business\BusinessController@memberMonthReport']);
	Route::get('companyMemberInfo', ['uses'=>'OrgManage\OrgmanageController@companyMemberInfo']);
    Route::get('totalMemberYearReport', ['uses'=>'Business\BusinessController@enterpriseYearAttend']);
    Route::any('memberYearReport', ['uses'=>'Business\BusinessController@memberYearReport']);
    Route::any('shipMemberYearReport', ['uses'=>'Business\BusinessController@shipMemberYearReport']);

    // Excel Output
    //기업소출근월보열람
    Route::get('enterpriseMonthAttendExcel', ['uses' => 'ExcelController@enterpriseMonthAttend']);
    //기업소출근일보종합
    Route::get('enterpriseDayAttendExcel', ['uses' => 'ExcelController@enterpriseDayAttend']);
    //개인사업계획보고서작성 일보등록
    Route::get('reportpersonExcel', ['uses'=>'ExcelController@reportPerson']);
    //개인사업계획보고서작성 주보등록
    Route::get('reportPersonUpdateWeekListExcel', ['uses'=>'ExcelController@reportPersonUpdateWeekList']);
    //개인사업계획보고서작성 월보등록
    Route::get('reportPersonUpdateMonthListExcel', ['uses'=>'ExcelController@reportPersonUpdateMonthList']);
    //개인사업계획보고서작성 기업소성원별 일보
    Route::get('reportPersonUpdateAllListExcel', ['uses'=>'ExcelController@reportPersonUpdateAllList']);
    //부서주보열람
    Route::get('reportUnitWeekReadExcel', ['uses'=>'ExcelController@reportUnitWeekRead']);
    //부서월보열람
    Route::get('reportUnitMonthReadExcel', ['uses'=>'ExcelController@reportUnitMonthRead']);
    //부서별주보열람
    Route::get('reportPerUnitExcel', ['uses'=>'ExcelController@reportPerUnit']);
    //부서별월보열람
    Route::get('reportPerUnitMonthExcel', ['uses'=>'ExcelController@reportPerUnitMonth']);
    //기업소주보열람
    Route::get('reportEnterpriseWeekReadExcel', ['uses'=>'ExcelController@reportEnterpriseWeekRead']);
    //기업소월보열람
    Route::get('reportEnterpriseMonthReadExcel', ['uses'=>'ExcelController@reportEnterpriseMonthRead']);
	//기업소성원별주보열람
	Route::get('reportPerMemberWeekExcel', ['uses'=>'ExcelController@reportPerMemberWeekExcel']);
	//기업소성원별월보열람
	Route::get('reportPerMemberMonthExcel', ['uses'=>'ExcelController@reportPerMemberMonthExcel']);
    //기업소년보열람
    Route::get('enterpriseYearAttendExcel', ['uses'=>'ExcelController@enterpriseYearAttendExcel']);

	//print
	Route::any('memberYearReportPrint', ['uses'=>'PrintController@memberYearReportPrint']);
	Route::any('shipMemberYearReportPrint', ['uses'=>'PrintController@shipMemberYearReportPrint']);
	Route::any('memberMonthAttendPrint', ['uses'=>'PrintController@memberMonthAttendPrint']);
	Route::any('shipMemberMonthAttendPrint', ['uses'=>'PrintController@shipMemberMonthAttendPrint']);
});

//게시판관련
Route::group(['prefix' => 'notice'], function () {
    Route::get('/', ['uses' => 'Notice\NoticeController@index']);
    Route::get('recommendNews',['uses'=>'Notice\NoticeController@recommendNews']);
    Route::get('newsTemaPage', ['uses'=>'Notice\NoticeController@newsTemaPage']);
    Route::get('showNewsListForTema',['uses'=>'Notice\NoticeController@showNewsListForTema']);

    Route::get('showNewsDetail/{id}.htm',['uses'=>'Notice\NoticeController@showNewsDetail']);
    Route::get('createNewsPage',['uses'=>'Notice\NoticeController@createNewsPage']);
    Route::get('createNewsPage/{id}.htm',['uses'=>'Notice\NoticeController@updateNewsPage']);
    Route::post('createNewsContent',['uses'=>'Notice\NoticeController@createNewsContent']);
    Route::post('newsRecommend',['uses'=>'Notice\NoticeController@newsRecommend']);
    Route::post('newsResponse',['uses'=>'Notice\NoticeController@newsResponse']);
    Route::post('saveNewTema', ['uses' => 'Notice\NoticeController@saveNewNewsTema']);
    Route::post('deleteNewsTema',['uses'=>'Notice\NoticeController@deleteNewsTema']);
});

//선박운영
Route::group(['prefix' => 'operation'], function() {
	Route::get('/', ['uses' => 'Operation\OperationController@index']);
	Route::get('operationPlan', ['uses' => 'Operation\OperationController@operationPlan']);
	Route::get('contract', ['uses' => 'Operation\OperationController@contract']);

	Route::get('yearPlanReport', ['uses' => 'Operation\OperationController@yearPlanReport']);
	Route::get('shipYearReport', ['uses' => 'Operation\OperationController@shipYearReport']);
	Route::get('shipMonthReport', ['uses' => 'Operation\OperationController@shipMonthReport']);
	Route::get('shipCountReport', ['uses' => 'Operation\OperationController@shipCountReport']);
	Route::get('oilSupply', ['uses'=>'Operation\OperationController@oilSupply']);

	// 배동태 >
	Route::get('homeMovement', ['as' => 'OperationMovement','uses' => 'Operation\OperationController@homeMovement']);
	Route::get('movement', ['as' => 'OperationMovement','uses' => 'Operation\OperationController@movement']);
	Route::post('addMovement', ['uses' => 'Operation\OperationController@addMovement']);
	Route::post('updateMovement', ['uses' => 'Operation\OperationController@updateMovement']);
	Route::post('removeMovement', ['uses' => 'Operation\OperationController@removeMovement']);
	Route::get('voyStatusManage', ['uses' => 'Operation\OperationController@voyStatusManage']);
	Route::post('updateVoyStatus', ['uses' => 'Operation\OperationController@updateVoyStatus']);
	Route::post('removeVoyStatus', ['uses' => 'Operation\OperationController@removeVoyStatus']);
	Route::get('voyStatusEventManage', ['uses' => 'Operation\OperationController@voyStatusEventManage']);
	Route::post('updateVoyStatusEvent', ['uses' => 'Operation\OperationController@updateVoyStatusEvent']);
	Route::post('deleteVoyStatusEvent', ['uses' => 'Operation\OperationController@deleteVoyStatusEvent']);
	Route::get('voyStatusTypeManage', ['uses' => 'Operation\OperationController@voyStatusTypeManage']);
	Route::post('updateVoyStatusType', ['uses' => 'Operation\OperationController@updateVoyStatusType']);
	Route::post('deleteVoyStatusType', ['uses' => 'Operation\OperationController@deleteVoyStatusType']);

	// 용선계약 >
	Route::post('addContract', ['uses' => 'Operation\OperationController@addContract']);
	Route::post('updateContract', ['uses' => 'Operation\OperationController@updateContract']);
	Route::get('getContract', ['uses' => 'Operation\OperationController@getContract']);
	Route::post('removeContract', ['uses' => 'Operation\OperationController@removeContract']);

	// 운영계획 > 년별 계획
	Route::post('updateYearPlan', ['uses' => 'Operation\OperationController@updateYearPlan']);
	Route::post('updateQuarterMonthPlan', ['uses' => 'Operation\OperationController@updateQuarterMonthPlan']);
	Route::post('removeYearPlan', ['uses' => 'Operation\OperationController@removeYearPlan']);
	Route::post('updateYearInputPlan', ['uses' => 'Operation\OperationController@updateYearInputPlan']);
	Route::post('getShipYearPlan', ['uses' => 'Operation\OperationController@getShipYearPlan']);

	//수입 및 지출
	Route::get('import', ['uses' => 'Operation\OperationController@import']);
	Route::post('updateShipInvoice', ['uses' => 'Operation\OperationController@updateShipInvoice']);
	Route::post('getSupplyElement', ['uses' => 'Operation\OperationController@getSupplyElement']);
	Route::post('deleteShipInvoice', ['uses' => 'Operation\OperationController@deleteShipInvoice']);


	// 항차타산
	Route::get('shipCountSimpleList', ['uses' => 'Operation\OperationController@shipCountSimpleList']);
	Route::get('shipCountSimple', ['uses' => 'Operation\OperationController@shipCountSimple']);
	Route::get('shipCountStandard', ['uses' => 'Operation\OperationController@shipCountStandard']);
	Route::get('shipCalc', ['uses' => 'Operation\OperationController@shipCalc']);
	Route::post('getSailDistance', ['uses' => 'Operation\OperationController@getSailDistance']);
	Route::post('getVoyList', ['uses' => 'Operation\OperationController@getVoyList']);
	Route::post('getVoyListAndShipSpeed', ['uses' => 'Operation\OperationController@getVoyListAndShipSpeed']);
	Route::get('voyCountCalculateInput', ['uses' => 'Operation\OperationController@voyCountCalculateInput']);
	Route::post('calculateVoyageData', ['uses' => 'Operation\OperationController@calculateVoyageData']);
	Route::post('showPortDistance', ['uses' => 'Operation\OperationController@showPortDistance']);
	Route::get('betweenPortDistance', ['uses' => 'Operation\OperationController@betweenPortDistance']);
	Route::post('voyLogByShipId', ['uses' => 'Operation\OperationController@voyLogByShipId']);
	Route::post('shipFuelCondition', ['uses' => 'Operation\OperationController@shipFuelCondition']);
	Route::post('updateStandardCp', ['uses' => 'Operation\OperationController@updateStandardCp']);
	Route::post('getVoyListAndCaculInfo', ['uses' => 'Operation\OperationController@getVoyListAndCaculInfo']);
	Route::post('updateVoyProfit', ['uses' => 'Operation\OperationController@updateVoyProfit']);

	// 항차일수분석
	Route::get('shipVoyAnalysis', ['uses' => 'Operation\OperationController@shipVoyAnalysis']);

    // 항구명관리
    Route::get('shipPortManage', ['uses' => 'Operation\OperationController@shipPortManage']);
    Route::post('registerShipPort', ['uses' => 'Operation\OperationController@registerShipPort']);
    Route::post('deleteShipPort', ['uses' => 'Operation\OperationController@deleteShipPort']);

	// 항해거리관리
	Route::get('navigtionDistance', ['uses' => 'Operation\OperationController@navigtionDistance']);
	Route::post('updateDistance', ['uses' => 'Operation\OperationController@updateDistance']);
	Route::post('deleteShipPort', ['uses' => 'Operation\OperationController@deleteShipPort']);

    // 화물명관리
    Route::get('cargoManage', ['uses' => 'Operation\OperationController@cargoManage']);
    Route::post('registerShipCargo', ['uses' => 'Operation\OperationController@registerShipCargo']);
    Route::post('deleteDistance', ['uses' => 'Operation\OperationController@deleteDistance']);

	//AC관리
	Route::get('ACManage', ['uses' => 'Operation\OperationController@ACManage']);
	Route::post('addACType', ['uses' => 'Operation\OperationController@addACType']);
	Route::post('deleteACType', ['uses' => 'Operation\OperationController@deleteACType']);
	Route::post('loadACDetail', ['uses' => 'Operation\OperationController@loadACDetail']);
	Route::post('addACDetail', ['uses' => 'Operation\OperationController@addACDetail']);
	Route::post('deleteACDetail', ['uses' => 'Operation\OperationController@deleteACDetail']);

	// Account관리
	Route::get('accountManage', ['uses' => 'Operation\OperationController@accountManage']);
	Route::post('addAccount', ['uses' => 'Operation\OperationController@addAccount']);
	Route::post('deleteAccount', ['uses' => 'Operation\OperationController@deleteAccount']);

	// Account관리
	Route::get('payModeManage', ['uses' => 'Operation\OperationController@payModeManage']);
	Route::post('addPayMode', ['uses' => 'Operation\OperationController@addPayMode']);
	Route::post('deletePayMode', ['uses' => 'Operation\OperationController@deletePayMode']);

    //Excel출력
    //용선계약
    Route::get('contractExcel', ['uses' => 'ExcelController@contract']);
    // 배동태
    Route::get('movementExcel', ['uses' => 'ExcelController@movement']);
    // 항차타산(표준)
    Route::get('shipCountStandardExcel', ['uses' => 'ExcelController@shipCountStandard']);
    // 항차일수분석
    Route::get('shipVoyAnalysisExcel', ['uses' => 'ExcelController@shipVoyAnalysis']);
    // 년계획수행종합
    Route::get('yearPlanReportExcel', ['uses' => 'ExcelController@yearPlanReport']);
    // 배별년계획수행종합
    Route::get('shipYearReportExcel', ['uses' => 'ExcelController@shipYearReport']);
    // 배별월계획수행종합
    Route::get('shipMonthReportExcel', ['uses' => 'ExcelController@shipMonthReport']);
    // 배별항차실적종합
    Route::get('shipCountReportExcel', ['uses' => 'ExcelController@shipCountReport']);
    // 연유공급
    Route::get('oilSupplyExcel', ['uses'=>'ExcelController@oilSupply']);

    //Print
    //항차타산표준
    Route::get('shipCountStandardPrint', ['uses' => 'PrintController@shipCountStandard']);
    //운임계산서
    Route::get('shipCalcPrint', ['uses' => 'PrintController@shipCalc']);
});

// 배등록
Route::group(['prefix' => 'shipManage'], function()
{
	Route:get('/', ['uses'=>'ShipManage\ShipRegController@index']);
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
	Route::post('uploadShipPicture', ['uses'=>'ShipManage\ShipRegController@uploadShipPicture']);
	Route::post('deleteShipPhotoImage', ['uses'=>'ShipManage\ShipRegController@deleteShipPhotoImage']);

	Route::get('shipCertList', ['uses'=>'ShipManage\ShipRegController@shipCertList']);
	Route::post('getShipCertInfo', ['uses'=>'ShipManage\ShipRegController@getShipCertInfo']);
	Route::post('updateCertInfo', ['uses'=>'ShipManage\ShipRegController@updateCertInfo']);
	Route::post('deleteShipCert', ['uses'=>'ShipManage\ShipRegController@deleteShipCert']);

	Route::get('shipCertManage', ['uses'=>'ShipManage\ShipRegController@shipCertManage']);
	Route::post('getCertType', ['uses'=>'ShipManage\ShipRegController@getCertType']);
	Route::post('updateCertType', ['uses'=>'ShipManage\ShipRegController@updateCertType']);
	Route::post('deleteShipCertType', ['uses'=>'ShipManage\ShipRegController@deleteShipCertType']);

	Route::get('shipEquipmentManage', ['uses'=>'ShipManage\ShipRegController@shipEquipmentManage']);
	Route::post('shipEquepmentByKind', ['uses'=>'ShipManage\ShipRegController@shipEquepmentByKind']);
	Route::any('getEquipmentDetail', ['uses'=>'ShipManage\ShipRegController@getEquipmentDetail']);
	Route::any('getSupplyHistory', ['uses'=>'ShipManage\ShipRegController@getSupplyHistory']);
	Route::any('getDiligenceDetail', ['uses'=>'ShipManage\ShipRegController@getDiligenceDetail']);
	Route::post('appendNewShipEquipment', ['uses'=>'ShipManage\ShipRegController@appendNewShipEquipment']);
	Route::post('appendNewShipDiligenceEquipment', ['uses'=>'ShipManage\ShipRegController@appendNewShipDiligenceEquipment']);
	Route::post('deleteShipEquipment', ['uses'=>'ShipManage\ShipRegController@deleteShipEquipment']);
	Route::post('shipSubEquipemntList', ['uses'=>'ShipManage\ShipRegController@shipSubEquipemntList']);
	Route::post('ajax/shipEquipment/SupplyDate/update', ['uses'=>'ShipManage\ShipRegController@ajaxSupplyDateUpdate']);

	Route::post('updateEquipmentProperty', ['uses'=>'ShipManage\ShipRegController@updateEquipmentProperty']);
	Route::post('deleteEquipmentProperty', ['uses'=>'ShipManage\ShipRegController@deleteEquipmentProperty']);
	Route::post('updateEquipmentPart', ['uses'=>'ShipManage\ShipRegController@updateEquipmentPart']);
	Route::post('deleteEquipmentPart', ['uses'=>'ShipManage\ShipRegController@deleteEquipmentPart']);
	Route::post('propertyTableEquipmentByDeviceID', ['uses'=>'ShipManage\ShipRegController@propertyTableEquipmentByDeviceID']);
	Route::post('partTableEquipmentByDeviceID', ['uses'=>'ShipManage\ShipRegController@partTableEquipmentByDeviceID']);
	Route::post('propertyTabEquipmentByDeviceID', ['uses'=>'ShipManage\ShipRegController@propertyTabEquipmentByDeviceID']);
	Route::post('partTabEquipmentByDeviceID', ['uses'=>'ShipManage\ShipRegController@partTabEquipmentByDeviceID']);


	Route::get('equipmentTypeManage', ['uses'=>'ShipManage\ShipRegController@equipmentTypeManage']);
	Route::post('updateEquipmentType', ['uses'=>'ShipManage\ShipRegController@updateEquipmentType']);
	Route::post('UpdateMainEquipment', ['uses'=>'ShipManage\ShipRegController@UpdateMainEquipment']);
	Route::post('UpdateEquipmentUnits', ['uses'=>'ShipManage\ShipRegController@UpdateEquipmentUnits']);
	Route::post('deleteEquipmentMainType', ['uses'=>'ShipManage\ShipRegController@deleteEquipmentMainType']);
	Route::post('deleteEquipmentSubType', ['uses'=>'ShipManage\ShipRegController@deleteEquipmentSubType']);
	Route::post('deleteEquipmentUnits', ['uses'=>'ShipManage\ShipRegController@deleteEquipmentUnits']);
	Route::get('shipISSACodeManage', ['uses'=>'ShipManage\ShipRegController@shipISSACodeManage']);
	Route::post('updateIssaCode', ['uses'=>'ShipManage\ShipRegController@updateIssaCode']);
	Route::post('deleteIssaCode', ['uses'=>'ShipManage\ShipRegController@deleteIssaCode']);

	Route::get('shipNameManage', ['uses'=>'ShipManage\ShipRegController@shipNameManage']);
	Route::post('deleteOriginShip', ['uses'=>'ShipManage\ShipRegController@deleteOriginShip']);
	Route::post('registerShipOrigin', ['uses'=>'ShipManage\ShipRegController@registerShipOrigin']);

	Route::get('shipPositionManage', ['uses'=>'ShipManage\ShipRegController@shipPositionManage']);
	Route::post('deleteShipPosition', ['uses'=>'ShipManage\ShipRegController@deleteShipPosition']);
	Route::post('registerShipPosition', ['uses'=>'ShipManage\ShipRegController@registerShipPosition']);

	Route::get('shipTypeManage', ['uses'=>'ShipManage\ShipRegController@shipTypeManage']);
	Route::post('deleteShipType', ['uses'=>'ShipManage\ShipRegController@deleteShipType']);
	Route::post('registerShipType', ['uses'=>'ShipManage\ShipRegController@registerShipType']);

	Route::get('shipISSACodeType', ['uses'=>'ShipManage\ShipRegController@shipISSACodeType']);
	Route::post('deleteISSACodeType', ['uses'=>'ShipManage\ShipRegController@deleteISSACodeType']);
	Route::post('registerISSACodeType', ['uses'=>'ShipManage\ShipRegController@registerISSACodeType']);

	Route::get('shipSTCWManage', ['uses'=>'ShipManage\ShipRegController@shipSTCWManage']);
	Route::post('deleteSTCWType', ['uses'=>'ShipManage\ShipRegController@deleteSTCWType']);
	Route::post('registerSTCWType', ['uses'=>'ShipManage\ShipRegController@registerSTCWType']);

    Route::get('memberCapacityManage', ['uses'=>'ShipManage\ShipRegController@memberCapacityManage']);
    Route::post('registerMemberCapacity', ['uses'=>'ShipManage\ShipRegController@registerMemberCapacity']);
    Route::post('deleteMemberCapacity', ['uses'=>'ShipManage\ShipRegController@deleteMemberCapacity']);

	Route::get('shipOthersManage', ['uses'=>'ShipManage\ShipRegController@shipOthersManage']);
	Route::post('registerShipOthers', ['uses'=>'ShipManage\ShipRegController@registerShipOthers']);
	Route::post('deleteShipOthers', ['uses'=>'ShipManage\ShipRegController@deleteShipOthers']);

    Route::get('shipPartManage', ['uses'=>'ShipManage\ShipRegController@shipPartManage']);
    Route::post('updateEquipmentPart', ['uses'=>'ShipManage\ShipRegController@updateEquipmentPart']);
    Route::post('deleteEquipmentPart', ['uses'=>'ShipManage\ShipRegController@deleteEquipmentPart']);

    // Excel 출력
    // 배제원
    Route::get('shipinfoExcel', ['uses'=>'ExcelController@loadShipGeneralInfos']);
    // 배증서목록
    Route::get('shipCertListExcel', ['uses'=>'ExcelController@shipCertList']);
    // 배증서종류
    Route::get('shipCertManageExcel', ['uses'=>'ExcelController@shipCertManage']);
    // 배별설비목록
    Route::get('shipEquepmentByKindExcel', ['uses'=>'ExcelController@shipEquepmentByKind']);
    // 기술자격목록
    Route::get('memberCapacityManageExcel', ['uses'=>'ExcelController@memberCapacityManageExcel']);

});

// 선원관리
Route::group(['prefix' => 'shipMember'], function() {
	Route:get('/', ['uses' => 'ShipManage\ShipMemberController@index']);
	Route::get('shipMember', ['uses' => 'ShipManage\ShipMemberController@loadShipMembers']);
	Route::get('registerShipMember', ['uses' => 'ShipManage\ShipMemberController@registerShipMember']);
	Route::post('showShipMemberDataTab', ['uses' => 'ShipManage\ShipMemberController@showShipMemberDataTab']);
	Route::post('updateMemberInfo', ['uses' => 'ShipManage\ShipMemberController@updateMemberInfo']);
	Route::post('updateMemberMainInfo', ['uses' => 'ShipManage\ShipMemberController@updateMemberMainInfo']);
	Route::post('updateMemberMainData', ['uses' => 'ShipManage\ShipMemberController@updateMemberMainData']);
	Route::post('updateMemberCapacityData', ['uses' => 'ShipManage\ShipMemberController@updateMemberCapacityData']);
	Route::post('updateMemberTrainingData', ['uses' => 'ShipManage\ShipMemberController@updateMemberTrainingData']);
	Route::post('registerMemberExamingData', ['uses' => 'ShipManage\ShipMemberController@registerMemberExamingData']);
	Route::post('deleteMemberExamingData', ['uses' => 'ShipManage\ShipMemberController@deleteMemberExamingData']);
	Route::post('showMemberExamSubMarks', ['uses' => 'ShipManage\ShipMemberController@showMemberExamSubMarks']);
	Route::post('saveExamSubMarks', ['uses' => 'ShipManage\ShipMemberController@saveExamSubMarks']);
	Route::post('deleteExamSubMarks', ['uses' => 'ShipManage\ShipMemberController@deleteExamSubMarks']);
	Route::post('deleteShipMember', ['uses'=>'ShipManage\ShipMemberController@deleteShipMember']);

	Route::get('totalShipMember', ['uses' => 'ShipManage\ShipMemberController@totalShipMember']);
	Route::get('memberCertList', ['uses' => 'ShipManage\ShipMemberController@memberCertList']);
	Route::get('integretedMemberExaming', ['uses' => 'ShipManage\ShipMemberController@integretedMemberExaming']);

    //Excel 출력
    // 선원등록부
    Route::get('shipMemberExcel', ['uses'=>'ExcelController@loadShipMembersExcel']);
    //선원명단
    Route::get('totalShipMemberExcel', ['uses' => 'ExcelController@totalShipMember']);
    //선원증서
    Route::get('memberCertListExcel', ['uses' => 'ExcelController@memberCertList']);
    //선원실력평가
    Route::get('integretedMemberExamingExcel', ['uses' => 'ExcelController@integretedMemberExaming']);

});

//선박기술관리
Route::group(['prefix' => 'shipTechnique'], function() {
	Route::get('/', ['uses' => 'ShipTechnique\ShipTechniqueController@index']);
	//수입 및 지출
	Route::get('import', ['uses' => 'ShipTechnique\ShipTechniqueController@import']);
	Route::post('updateShipInvoice', ['uses' => 'Operation\OperationController@updateShipInvoice']);
	Route::post('getSupplyElement', ['uses' => 'Operation\OperationController@getSupplyElement']);
	Route::post('deleteShipInvoice', ['uses' => 'Operation\OperationController@deleteShipInvoice']);

    //설비, 부속, 자재 공급계획등록페지
    Route::get('supplyplan',['uses'=>'ShipTechnique\ShipEquipmentController@supplyPlan']);

	Route::get('shipRepairRegister', ['uses' => 'ShipTechnique\ShipTechniqueController@shipRepairRegister']);
	Route::get('shipRepairAllBrowse', ['uses' => 'ShipTechnique\ShipTechniqueController@shipRepairAllBrowse']);
	Route::get('shipRepairDetail', ['uses' => 'ShipTechnique\ShipTechniqueController@shipRepairDetail']);
	Route::post('updateRepair', ['uses' => 'ShipTechnique\ShipTechniqueController@updateRepair']);
	Route::post('getVoyList', ['uses' => 'ShipTechnique\ShipTechniqueController@getVoyList']);
	Route::post('getVoyListSearch', ['uses' => 'ShipTechnique\ShipTechniqueController@getVoyListSearch']);

	Route::get('shipEquip', ['uses' => 'ShipTechnique\ShipTechniqueController@shipEquip']);

	Route::get('shipAccidentRegister', ['uses' => 'ShipTechnique\ShipTechniqueController@shipAccidentRegister']);
	Route::get('shipAccidentSearch', ['uses' => 'ShipTechnique\ShipTechniqueController@shipAccidentSearch']);
	Route::get('shipAccidentAllBrowse', ['uses' => 'ShipTechnique\ShipTechniqueController@shipAccidentAllBrowse']);
	Route::get('shipAccidentSearchAll', ['uses' => 'ShipTechnique\ShipTechniqueController@shipAccidentSearchAll']);
	Route::get('shipAccidentDetail', ['uses' => 'ShipTechnique\ShipTechniqueController@shipAccidentDetail']);
	Route::post('updateAccident', ['uses' => 'ShipTechnique\ShipTechniqueController@updateAccident']);

	Route::get('shipSurveyRegister', ['uses' => 'ShipTechnique\ShipTechniqueController@shipSurveyRegister']);
	Route::get('shipSurveySearch', ['uses' => 'ShipTechnique\ShipTechniqueController@shipSurveySearch']);
	Route::get('shipSurveyAllBrowse', ['uses' => 'ShipTechnique\ShipTechniqueController@shipSurveyAllBrowse']);
	Route::get('shipSurveySearchAll', ['uses' => 'ShipTechnique\ShipTechniqueController@shipSurveySearchAll']);
	Route::get('shipSurveyDetail', ['uses' => 'ShipTechnique\ShipTechniqueController@shipSurveyDetail']);
	Route::post('updateSurvey', ['uses' => 'ShipTechnique\ShipTechniqueController@updateSurvey']);

	Route::post('RepairDelete', ['uses'=>'ShipTechnique\ShipTechniqueController@RepairDelete']);
	Route::post('AccidentDelete', ['uses'=>'ShipTechnique\ShipTechniqueController@AccidentDelete']);
	Route::post('SurveyDelete', ['uses'=>'ShipTechnique\ShipTechniqueController@SurveyDelete']);

//공급신청등록페지
	Route::get('shipRepair', ['uses' => 'ShipTechnique\ShipTechniqueController@shipRepair']);
	Route::get('supplyRecord',['uses'=>'ShipTechnique\ShipTechniqueController@loadSupplyRecord']);
	Route::post('getVoyListOfShip',['uses'=>'ShipTechnique\ShipTechniqueController@getVoyListOfShip']);
	Route::post('addApplication',['uses'=>'ShipTechnique\ShipTechniqueController@addApplication']);
	Route::post('saveSupplyInfo',['uses'=>'ShipTechnique\ShipTechniqueController@saveSupplyInfo']);
	Route::post('deleteSupplyInfo',['uses'=>'ShipTechnique\ShipTechniqueController@deleteSupplyInfo']);
	Route::post('getInfo',['uses'=>'ShipTechnique\ShipTechniqueController@getInfo']);
	Route::get('getHistory',['uses'=>'ShipTechnique\ShipTechniqueController@getHistory']);
//공급정형열람페지
    Route::get('showSupplyInfo',['uses'=>'ShipTechnique\ShipTechniqueController@showSupplyInfo']);
    Route::get('detailSupplyInfo',['uses'=>'ShipTechnique\ShipTechniqueController@detailSupplyInfo']);
    Route::get('showDetailSupplyInfo',['uses'=>'ShipTechnique\ShipTechniqueController@showDetailSupplyInfo']);
    Route::post('getEquipmentKindInfo',['uses'=>'ShipTechnique\ShipTechniqueController@getEquipmentKindInfo']);
    Route::post('getEquipmentInfo',['uses'=>'ShipTechnique\ShipTechniqueController@getEquipmentInfo']);
    Route::post('getEquipmentDetailInfo',['uses'=>'ShipTechnique\ShipTechniqueController@getEquipmentDetailInfo']);
//공급계획열람/등록페지
    Route::post('supplyPlanAdd',['uses'=>'ShipTechnique\ShipEquipmentController@supplyPlanAdd']);

	//공급종합
	Route::get('supplyReport', ['uses'=>'ShipTechnique\ShipEquipmentController@supplyReport']);

    //Excel출력
    //설비, 부속, 자재 공급계획등록페지
    Route::get('supplyplanExcel',['uses'=>'ExcelController@supplyPlan']);
    //신청공급등록
    Route::get('supplyRecordExcel',['uses'=>'ExcelController@loadSupplyRecord']);
    //공급정형열람페지
    Route::get('showSupplyInfoExcel',['uses'=>'ExcelController@showSupplyInfo']);

    //Print
    //배수리보고서종합열람
    Route::get('shipRepairAllBrowsePrint', ['uses' => 'PrintController@shipRepairAllBrowse']);
	Route::get('shipRepairAllBrowseExcel', ['uses' => 'ExcelController@shipRepairAllBrowse']);
    //설비, 부속, 자재 공급계획등록페지
    Route::get('supplyplanPrint',['uses'=>'PrintController@supplyPlan']);
    //배사고보고서종합열람
    Route::get('shipAccidentAllBrowsePrint', ['uses' => 'PrintController@shipAccidentAllBrowse']);
	Route::get('shipAccidentAllBrowseExcel', ['uses' => 'ExcelController@shipAccidentAllBrowse']);
    //배검사보고서종합열람
    Route::get('shipSurveyAllBrowsePrint', ['uses' => 'PrintController@shipSurveyAllBrowse']);
	Route::get('shipSurveyAllBrowseExcel', ['uses' => 'ExcelController@shipSurveyAllBrowse']);
});

//선박기술관리
Route::group(['prefix' => 'convert'], function() {
	Route::get('convertVoyLog', ['uses' => 'Convert\convertController@convertVoyId']);
	Route::get('convert', ['uses' => 'Convert\convertController@convertInvoiceAccountId']);
	Route::get('showIcon', ['uses' => 'Convert\convertController@showIcon']);

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
