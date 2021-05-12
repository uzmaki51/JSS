<?php
/**
 * AIOBOT Admin Page : Master data
 * 2020/02/28 Created by H(S
 *
 * @author H(S
 */

define('MIN_TRANSFER_AMOUNT', 0.01);
define('EMPTY_STRING', '');
define('INT_ZERO', 0);
define('STR_ZERO', '0');
define('IS_ERROR', 1);
define('IS_NOT_ERROR', 0);

define('DEFAULT_PASS', '123456');
define('HTTP_METHOD_POST', 'POST');

#UserRole
define('SUPER_ADMIN', 1);
define('EMPTY_DATE', '0000-00-00');

# Status
define('STATUS_BANNED', 0);
define('STATUS_ACTIVE', 1);
$StatusData = array(
    STATUS_BANNED       =>  ['Banned', 'danger'],
    STATUS_ACTIVE       =>  ['Active', 'success'],
);

# ReportTypeData
define('REPORT_TYPE_EVIDENCE_IN',   'Credit');
define('REPORT_TYPE_EVIDENCE_OUT',  'Debit');
define('REPORT_TYPE_CONTRACT',      'Contract');
define('REPORT_TYPE_OTHER',         'Other');
$ReportTypeData = array(
	REPORT_TYPE_EVIDENCE_IN         => '支出',
	REPORT_TYPE_CONTRACT            => '合同',
	REPORT_TYPE_EVIDENCE_OUT        => '收入',
	REPORT_TYPE_OTHER 		        => '其他',
	
);

# Issuer
define('ISSUER_TYPE_MA', 0);
define('ISSUER_TYPE_RO', 1);
define('ISSUER_TYPE_IC', 2);
define('ISSUER_TYPE_SS', 3);
define('ISSUER_TYPE_EL', 4);
$IssuerTypeData = array(
	ISSUER_TYPE_MA      => 'MA',
	ISSUER_TYPE_RO      => 'RO',
	ISSUER_TYPE_IC      => '保险社',
	ISSUER_TYPE_SS      => '服务站',
	ISSUER_TYPE_EL      => '其他',
);

# FileUpload Status
define('IS_FILE_KEEP',      0);
define('IS_FILE_DELETE',    1);
define('IS_FILE_UPDATE',    2);

# IncomeData
define('INCOME_UNIM',       '1');
define('INCOME_BODY_FEE',   '2');
define('INCOME_ELSE',       '3');
$InComeData = array(
	INCOME_UNIM         => '运费',
	INCOME_BODY_FEE     => '滞期费',
	INCOME_ELSE         => '其他',
);

define('MCRYPT_RIJNDAEL_128', '123456');

# OutcomeData
define('OUTCOME_FEE1',       '1');
define('OUTCOME_FEE2',       '2');
define('OUTCOME_FEE3',       '3');
define('OUTCOME_FEE4',       '4');
define('OUTCOME_FEE5',       '5');
define('OUTCOME_FEE6',       '6');
define('OUTCOME_FEE7',       '7');
define('OUTCOME_FEE8',       '8');
define('OUTCOME_FEE9',       '9');
$OutComeData = array(
	OUTCOME_FEE1    => '港费',
	OUTCOME_FEE2    => '加油',
	OUTCOME_FEE3    => '工资',
	OUTCOME_FEE4    => '佣金',
	OUTCOME_FEE5    => '物料',
	OUTCOME_FEE6    => '修理',
	OUTCOME_FEE7    => '证书',
	OUTCOME_FEE8    => '保险',
	OUTCOME_FEE9    => '其他',
);

$FeeTypeData = array(
	REPORT_TYPE_CONTRACT           => [],
	REPORT_TYPE_EVIDENCE_OUT       => $OutComeData,
	REPORT_TYPE_EVIDENCE_IN        => $InComeData,
	REPORT_TYPE_OTHER			   => []
);
# UserLabelInfo
define('IS_STAFF',          0);
define('IS_CAPTAIN',        1);
define('IS_SECRETARY',      2);
define('IS_SHAREHOLDER',    100);
$UserLabelInfo = array(
	IS_CAPTAIN          => ['总经理', 'success'],
	IS_STAFF            => ['职员',   'info'],
	IS_SHAREHOLDER      => ['船东',   'danger'],
	IS_SECRETARY        => ['簿记',   'warning'],
);

# ReportTypeData
define('REPORT_EVIDENCE_OUT',   2);
define('REPORT_EVIDENCE_IN',    1);
define('REPORT_CONTACT',        3);
define('REPORT_OTHER',          4);
$ReportTypeLabelData = array(
	REPORT_EVIDENCE_IN      => ['支出',      'danger'],
	REPORT_EVIDENCE_OUT     => ['收入',       'info'],
	REPORT_CONTACT          => ['合同',    'primary'],
	REPORT_OTHER            => ['其他',    'secondary'],
);

define('CNY_LABEL', 'CNY');
define('USD_LABEL', 'USD');
define('EUR_LABEL', 'EUR');
$CurrencyLabel = array(
	CNY_LABEL   =>  '¥',
	USD_LABEL   =>  '$',
	EUR_LABEL   =>  '€',
);

#Inventory Status Data
define('INVENTORY_STATUS_UNKNOWN',  0);
define('INVENTORY_STATUS_NEW',      1);
define('INVENTORY_STATUS_RECYCLE',  2);
define('INVENTORY_STATUS_OLD',      3);
$InventoryStatusData = array(
	INVENTORY_STATUS_UNKNOWN        => ['未定',       'secondary'],
	INVENTORY_STATUS_NEW            => ['新品',       'primary'],
	INVENTORY_STATUS_RECYCLE        => ['再生',       'info'],
	INVENTORY_STATUS_OLD            => ['二手',       'danger'],
);

#TermListData
define('TERM_MONTH_1_IN',   0);
define('TERM_MONTH_1',      1);
define('TERM_MONTH_3',      3);
define('TERM_MONTH_6',      6);
define('TERM_YEAR_1',       12);
define('TERM_YEAR_PLUS',    '+');
$TermData = array(
	TERM_MONTH_1_IN =>  ['1月以内',       'primary'],
	TERM_MONTH_1    =>  ['1月以上',       'primary'],
	TERM_MONTH_3    =>  ['3月以上',       'primary'],
	TERM_MONTH_6    =>  ['6月以上',       'info'],
	TERM_YEAR_1     =>  ['1年以上',       'danger'],
);


# Employee Status
define('EMPLOYEE_STATUS_RETIREMENT',    0);
define('EMPLOYEE_STATUS_WORK',          1);
$EmployeeStatusData = array(
	EMPLOYEE_STATUS_RETIREMENT      => ['卸任', 'secondary'],
	EMPLOYEE_STATUS_WORK            => ['登录', 'primary']
);

# Accident Status
define('ACCIDENT_TYPE_RUNGROUND', 	1);
define('ACCIDENT_TYPE_CLASH', 		2);
define('ACCIDENT_TYPE_BREAKDOWN', 	3);
define('ACCIDENT_TYPE_LOSE', 		4);
define('ACCIDENT_TYPE_SHORTAGE', 	5);
$AccidentTypeData = array(
	ACCIDENT_TYPE_RUNGROUND		=> ['搁浅', 'primary'],
	ACCIDENT_TYPE_CLASH			=> ['冲突', 'info'],
	ACCIDENT_TYPE_BREAKDOWN		=> ['故障破损', 'warning'],
	ACCIDENT_TYPE_LOSE			=> ['丢失', 'secondary'],
	ACCIDENT_TYPE_SHORTAGE		=> ['货物不足', 'danger'],
);

# ShipTypeData
define('SHIP_TYPE_A_1', 1);
define('SHIP_TYPE_B_1', 2);
define('SHIP_TYPE_B_2', 3);
define('SHIP_TYPE_B_3', 4);
$ShipTypeData = array(
	SHIP_TYPE_A_1		=> 'Type "A"',
	SHIP_TYPE_B_1		=> 'Type "B"',
	SHIP_TYPE_B_2		=> 'Type "B" with reduced freeboard',
	SHIP_TYPE_B_3		=> 'Type "B" with increased freeboard',
);

# ShipRegStatusType
define('SHIP_REG_STATUS_PRO', 1);
define('SHIP_REG_STATUS_PER', 2);
define('SHIP_REG_STATUS_DEL', 3);
$ShipRegStatus = array(
	SHIP_REG_STATUS_PRO     => 'PRO',
	SHIP_REG_STATUS_PER     => 'PER',
	SHIP_REG_STATUS_DEL     => 'DEL',
);

# ReportStatusData
define('REPORT_STATUS_REQUEST',     0);
define('REPORT_STATUS_ACCEPT',      1);
define('REPORT_STATUS_REJECT',      2);
define('REPORT_STATUS_DRAFT',       3);

$ReportStatusData = array(
	REPORT_STATUS_REQUEST   => ['等批', 'primary'],
	REPORT_STATUS_ACCEPT    => ['通过', 'info'],
	REPORT_STATUS_REJECT    => ['否决', 'secondary'],
);

$NationalityData = array(
	0      => 'BANGLADESH',
	1      => 'CHINESE',
	2      => 'MYANMAR',
);


# CPTypeData
define('CP_TYPE_VOY',   'VOY');
define('CP_TYPE_TC',    'TC');
$CPTypeData = array(
	CP_TYPE_VOY     => 'VOY',
	CP_TYPE_TC      => 'TC'
);

define('QTY_TYPE_MOLOO', 1);
define('QTY_TYPE_MOLCO', 2);
$QtyTypeData = array(
	QTY_TYPE_MOLOO		=> 'MOLOO',
	QTY_TYPE_MOLCO		=> 'MOLCO'
);
define('BANK_TYPE_0', 0);
define('BANK_TYPE_1', 1);
define('BANK_TYPE_2', 2);
$BankData = array(
	BANK_TYPE_0		=> '农行',
	BANK_TYPE_1		=> '华夏',
	BANK_TYPE_2		=> '大连'
);


define('DYNAMIC_SUB_ELSE', 			1);
define('DYNAMIC_SUB_WEATHER', 		2);
define('DYNAMIC_SUB_SUPPLY', 		3);
define('DYNAMIC_SUB_REPAIR', 		4);
define('DYNAMIC_SUB_WAITING', 		5);
define('DYNAMIC_SUB_LOADING', 		6);
define('DYNAMIC_SUB_SALING', 		7);
define('DYNAMIC_SUB_DISCH', 		8);

$DynamicSub = array(
	DYNAMIC_SUB_ELSE		=> '其他',
	DYNAMIC_SUB_WEATHER		=> '天气',
	DYNAMIC_SUB_SUPPLY		=> '供应',
	DYNAMIC_SUB_REPAIR		=> '修理',
	DYNAMIC_SUB_WAITING		=> '待泊',
	DYNAMIC_SUB_LOADING		=> '装货',
	DYNAMIC_SUB_SALING		=> '航行',
	DYNAMIC_SUB_DISCH		=> '卸货',
);

# Dynamic Status
define('DYNAMIC_DEPARTURE', 		1);
define('DYNAMIC_SAILING', 			2);
define('DYNAMIC_ANCHORING', 		3);
define('DYNAMIC_ARRIVAL', 			4);
define('DYNAMIC_POB', 				18);
define('DYNAMIC_BERTH', 			6);
define('DYNAMIC_UNBERTH', 			7);
define('DYNAMIC_CMNC', 				8);
define('DYNAMIC_LOADING', 			9);
define('DYNAMIC_CMPLT_LOADING', 	10);
define('DYNAMIC_CMNC_DISCH', 		11);
define('DYNAMIC_DISCHARG', 			12);
define('DYNAMIC_CMPLT_DISCH', 		13);
define('DYNAMIC_STOP', 				14);
define('DYNAMIC_RESUME', 			15);
define('DYNAMIC_DOCKING', 			16);
define('DYNAMIC_UNDOCKING', 		17);
define('DYNAMIC_WAITING', 			5);

$DynamicStatus = array(
	DYNAMIC_DEPARTURE 			=> ['DEPARTURE', [
			DYNAMIC_SUB_ELSE, 
			DYNAMIC_SUB_WEATHER, 
			DYNAMIC_SUB_SUPPLY, 
			DYNAMIC_SUB_REPAIR
		]],
	DYNAMIC_SAILING 			=> ['SAILING', [
			DYNAMIC_SUB_SALING
	]],
	DYNAMIC_ANCHORING 			=> ['ANCHORING', [
			DYNAMIC_SUB_SALING,
			DYNAMIC_SUB_WEATHER,
			DYNAMIC_SUB_SUPPLY,
			DYNAMIC_SUB_LOADING,
			DYNAMIC_SUB_REPAIR,
			DYNAMIC_SUB_DISCH,
			DYNAMIC_SUB_WAITING,
			DYNAMIC_SUB_ELSE
	]],
	DYNAMIC_ARRIVAL 			=> ['ARRIVAL', [
		DYNAMIC_SUB_SALING
	]],
	DYNAMIC_WAITING 			=> ['WAITING', [
		DYNAMIC_SUB_WEATHER, 
		DYNAMIC_SUB_SUPPLY, 
		DYNAMIC_SUB_REPAIR,
		DYNAMIC_SUB_LOADING,
		DYNAMIC_SUB_DISCH,
		DYNAMIC_SUB_WAITING,
		DYNAMIC_SUB_ELSE
	]],
	DYNAMIC_POB 				=> ['POB', [
		DYNAMIC_SUB_WAITING
	]],
	DYNAMIC_BERTH 				=> ['BERTH', [
		DYNAMIC_SUB_SALING,
		DYNAMIC_SUB_WAITING,
	]],
	DYNAMIC_UNBERTH 			=> ['UNBERTH', [
			DYNAMIC_SUB_ELSE
	]],
	DYNAMIC_CMNC 				=> ['CMNC LOADING', [
			DYNAMIC_SUB_ELSE,
			DYNAMIC_SUB_WAITING
	]],
	DYNAMIC_LOADING 			=> ['LOADING', [
		DYNAMIC_SUB_LOADING
	]],
	DYNAMIC_CMPLT_LOADING 		=> ['CMPLT LOADING', [
		DYNAMIC_SUB_LOADING
	]],
	DYNAMIC_CMNC_DISCH 			=> ['CMNC DISCH', [
		DYNAMIC_SUB_ELSE,
		DYNAMIC_SUB_WAITING,
	]],
	DYNAMIC_DISCHARG 			=> ['DISCHARG', [
		DYNAMIC_SUB_DISCH,
	]],
	DYNAMIC_CMPLT_DISCH 			=> ['CMPLT DISCH', [
		DYNAMIC_SUB_DISCH,
	]],
	DYNAMIC_STOP 				=> ['STOP', [
		DYNAMIC_SUB_LOADING,
		DYNAMIC_SUB_DISCH
	]],
	DYNAMIC_RESUME 				=> ['RESUME', [
		DYNAMIC_SUB_ELSE, 
		DYNAMIC_SUB_WEATHER, 
		DYNAMIC_SUB_SUPPLY, 
		DYNAMIC_SUB_REPAIR
	]],
	DYNAMIC_DOCKING 			=> ['DOCKING', [
		DYNAMIC_SUB_ELSE
	]],
	DYNAMIC_UNDOCKING 			=> ['UNDOCKING', [
		DYNAMIC_SUB_REPAIR
	]],
);





 $g_masterData = array(
 	    'ReportTypeData'	            => $ReportTypeData,
	    'ReportTypeLabelData'	        => $ReportTypeLabelData,
	    'UserLabelInfo'	                => $UserLabelInfo,
	    'InComeData'                    => $InComeData,
        'OutComeData'                   => $OutComeData,
	    'FeeTypeData'                   => $FeeTypeData,
	    'CurrencyLabel'                 => $CurrencyLabel,
	    'InventoryStatusData'           => $InventoryStatusData,
	    'TermData'                      => $TermData,
		'EmployeeStatusData'            => $EmployeeStatusData,
		'AccidentTypeData'            	=> $AccidentTypeData,
		'ShipTypeData'					=> $ShipTypeData,
	    'ShipRegStatus'					=> $ShipRegStatus,
	    'ReportStatusData'			    => $ReportStatusData,
		'NationalityData'				=> $NationalityData,
	    'IssuerTypeData'				=> $IssuerTypeData,
		'CPTypeData'				    => $CPTypeData,
	    'QtyTypeData'				    => $QtyTypeData,	
		'BankData'				    	=> $BankData,
		'DynamicStatus'				    => $DynamicStatus,
		'DynamicSub'					=> $DynamicSub,

 );
