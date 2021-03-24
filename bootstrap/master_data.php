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


#UserRole
define('SUPER_ADMIN', 1);

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

# IncomeData
define('INCOME_UNIM',       '运费');
define('INCOME_BODY_FEE',   '滞期费');
define('INCOME_ELSE',       '其他收入');
$InComeData = array(
	INCOME_UNIM         => '运费',
	INCOME_BODY_FEE     => '滞期费',
	INCOME_ELSE         => '其他',
);

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
	CNY_LABEL   =>  '￥',
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
define('REPORT_STATUS_DRAFT',      3);

$ReportStatusData = array(
	REPORT_STATUS_REQUEST   => ['等批', 'primary'],
	REPORT_STATUS_ACCEPT    => ['通过', 'info'],
	REPORT_STATUS_REJECT    => ['否决', 'secondary'],
);




define('HTTP_METHOD_POST', 'POST');


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

 );
