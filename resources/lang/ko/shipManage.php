<?php
/**
 * Created by PhpStorm.
 * User: 崔文峰
 * Date: 2017.10.27
 * Time: 上午 7:02
 */

return [

    /*
    |--------------------------------------------------------------------------
    | Global Language Lines
    |--------------------------------------------------------------------------
    |
    | The following language lines are used by the global website.
    |
    */
    "shipinfo"  => [
        'No' => '번호',
        'ShipName(structure)' => '船舶名称(기구직제)',
        'shipName' => '船舶名称',
        'Owner' => '登记선주',
        'Builder' => '건조지',
        'Class' => '선급',
        'IMO' => '국제해사<br>기구번호',
        'Flag' => '국적',
        'Builder' => '건조지',
        'Port of Registry' => '선적항',
        'Ship Type' => '배종류',
        'Displacement' => '만재톤수(MT)',
        'GT' => '총톤수',
        'NT' => '순톤수',
        'BM' => '너비(m)',
        'DM' => '높이(m)',
        'Draught' => '만재흘수(m)',
        'MMSI' => '해상이동식별번호',
        'LOA' => '배길이/수선간/협약(m)',
        'DeadWeight' => '적재배数量(MT)',
        'Hold' => '창구<br>(수 / 규격)',
        'Lifting Device' => '권양장치',
        'M/E' => '주기관',
        'A/E' => '보조기관',
        'Anchorage Engine' => '정박기관',
        'Boiler' => '보이라',
        'Fuel' => '연유소비(중유/디젤유/윤활유)',
        'Persons' => '승선인원수',
        'ISM' => '안전관리회사',
        'Call Sign' => '호출부호',
        'Build Date' => '건조年',
        'INMARSAT' => '인마싸트주소',
        'DeadWeight' => '적재톤수(MT)',
        'HatchWays' => '창구뚜껑<br>(수/규격)',
        'Container' => '콘테나수<br>(갑판우/창구안)',
        'M/E number' => '(수/编号/형식/출력/회전수)',
        'A/E number' => '(수/编号/형식/출력/회전수/전압)',
        'Anchorage number' => '(수/编号/형식/출력/회전수/전압)',
        'Boiler number' => '(수/编号/형식)',
        'Summer' => '여름조건',
        'Winder' => '겨울조건',
        'MSMN' => '최소안전정원수',
    ],

    "registerShipData"  => [
        'OrderNo' => '순서',
        'Duty' => '승선직무',
        'STCW code' => 'STCW규정코드',
        'Persons' => '인원수',
    ],

    "tabMenu"  => [
        'General' => '일반',
        'Hull/Cargo' => '선체',
        'Machinery' => '주요설비',
        'MSMC' => '최소안전정원',
        'Photo' => '사진',
    ],

    "General"  => [
        'ShipName' => '船舶名称',
        'ShipName(structure)' => '船舶名称(기구직제)',
        'Class' => '선급',
        'RegNo' => '登记번호',
        'RegType' => '상태',
        'SerialNo' => '编号 ',
        'CallSign' => '호출부호',
        'MMSI' => '해상이동식별수자',
        'IMO_no' => '국제해사기구번호',
        'INMARSAT' => '인마싸르',
        'OriginalName' => '이전 船舶名称',
        'Flag' => '국적',
        'port of Reg' => '선적항',
        'Owner_Cn' => '회사명(조/영)',
        'Owner_en' => '선주(영문)',
        'Owner_Address_Cn'  =>  '주소(조/영)',
        'Owner_Address_en' => '주소(영문)',
        'owner' =>  '선주',
        'Address_Cn'  =>  '주소(조/영)',
        'Address_en' => '주소(영문)',
        'Tel No' => '전화',
        'Fax No' => '확스번호',
        'Email Address' => '전자우편주소',
        'ISM Company_kn' => '회사명(조/영)',
        'ISM Company_en' => '안전관리회사(영문)',
        'ISM' => '안전관리회사',
        'Ship Type' => '배종류',
        'GrossTon' => '총톤수',
        'NetTon' => '순톤수',
        '(DeadWeight)mt' => '적재톤수 [MT]',
        '(Displacement)mt' => '배数量 [MT]',
        '(Ballast)㎥' => '발라스트용적 [㎥]',
        '(FuelBunkers)㎥' => '연유탕크용적 [㎥]',
        '(ShipBuilder)m' => '건조지',
        'BuildDate/Place' => '건조날자/地点',
        '(KeelDate)m' => '룡골놓은날자',
        '(LaunchDate)m' => '진수날자',
        '(DeliveryDate)m' => '인도날자',
        '(ConversionDate)m' => '개조날자',
        '(LOA)m' => '길이 [m]',
        '(LBP)m' => '수선간길이 [m]',
        '(Lconvention)m' => '협약길이 [m]',
        '(BM)m' => '너비 [m]',
        '(DM)m' => '높이 [m]',
        '(Draught)m' => '만재홀수 [m]',
        '(Bridge)m' => '선교 [m]',
        '(Forecastle)m' => '선수루 [m]',
        '(Poop)m' => '선미 [m]',
        '(Deckhouse)m' => '갑판실 [m]',
        'Registeration Date' => '동록날자',
        'Renewal Date' => '갱신날자',
        'Expiry Date' => '만기날자',
        'Conditional Date' => '림시登记날자',
        'Deletion Date' => '삭제날자',
    ],

    "Hull"  => [
        'HullNo' => '선체번호',
        'Decks' => '갑판수',
        'Bulkheads' => '격벽수',
        '(Grain/Bale)㎥' => '알곡/짐짝 [㎥]',
        'Details' => '仔细',
        'HatchWays' => '창구뚜껑',
        'Hold' => '창구',
        'Number' => '수',
        'Size' => '치수, 형식',
        'Containers' => '콘테나수',
        'On Deck' => '갑판우',
        'In Hold (TEU)' => '창구안',
        'Lifting Device' => '권양장치',
    ],

    "Machinery"  => [
        'No/Type Engine' => '编号/형식/수',
        'Cylinder Bore/Stroke' => '기통직경/행정',
        'Power' => '출력(Kw)',
        'rpm' => '회전수(r/min)',
        'Manufacturer' => '제작자',
        'AddressEngMaker' => '제작地点',
        'EngineDate' => '제작날자',
        'Speed' => '속도(Kn)',
        'Generator Set' => '编号/형식/수',
        'Output' => '출력(kw)/회전수/전압',
        'Anchorage Engine' => '정박기관',
        'rpm/volte' => '회전수/전압',
        'Boiler Type & Number' => '编号/형식/수',
        'Boiler * Pressure * HeatingSurface' => '압력*전열면적',
        'Manufacturer' => '제작자',
        'AddressBoilerMaker' => '제작地点',
        'BoilerDate' => '제작날자',
        'FO' => '중유(MT/day)',
        'DO' => '디젤유(MT/day)',
        'LO' => '윤활유(Kg/day)',
        'BW' => '발라스트탕크',
        'FW' => '청수탕크',
        'Fuel'=>'연유종류',
        'Cond'=>'상태',
        'Sail'=>'항해',
        'L/D'=>'상하선',
        'Idle'=>'대기',
        'Capacity'=>'용적 [㎥]',
        'Descript'=>'무게 [MT]',
        'Kind'=>'탕크종류',
        'FuelTank'=>'탕크',
        'Summer'=>'여름조건',
        'Winter'=>'겨울조건',
        'Fuel Consumption'=>'연유소비기준',
        'ME TYPE'   =>  '주기관',
        'AE TYPE'   =>  '발전기관',
        'Boiler'    =>  '보이라',
        'FOT'   =>  '중유탕크',
        'DOT'   =>  '디젤유탕크',
        'BWT'   =>  '발라스트탕크',
        'FWT'   =>  '청수탕크'
    ],

    "shipCertlist"  => [
        'No' => '번호',
        'ShipName' => '船舶名称',
        'EnglishName' => '船舶名称(영문)',
        'CertName' => '증서명',
        'Kind' => '종류',
        'Issuing Authoriy' => '발급기관',
        'RegStatus' => '登记상태',
        'Date of Expiry' => '발급날자',
        'Scan'  =>  '사본',
        'Date of Issue' => '만기날자'
    ],

    "CertManage"  => [
        'No' => '번호',
        'RefNo' => '참고번호',
        'CertName_Cn' => '증서명(조문)',
        'CertName_En' => '증서명(영문)',
        'Kind' => '종류',
        'Description' => '说明',
    ],

    "EquipmentManage"  => [
        'PIC' => '담당자',
        'Equipment_Cn' => '设备名称(조문)',
        'Equipment_en' => '设备名称(영문)',
        'Label' => '编号',
        'Type/Model' => '형식/형',
        'S/N' => '编号 ',
        'IssaCode' => 'Issa Code',
        'Qty' => '数量',
        'Maker' => '제작자',
        'M`Year' => '제작年',
        'Remark' => '备注',
        'Select of Equipment Dept'  =>  '설비부문선택',
        'Equipment Type'    =>  '설비종류',
        'Tool Type'    =>  '부속종류',
    ],

    "EquipmentDetail"  => [
        'PIC' => '담당자',
        'Equipment_Cn' => '设备名称(조문)',
        'Equipment_en' => '设备名称(영문)',
        'Label' => '编号',
        'Type/Model' => '형식/형',
        'S/N' => '编号 ',
        'IssaCode' => 'Issa Code',
        'Qty' => '数量',
        'Maker' => '제작자',
        'M`Year' => '제작年',
        'Remark' => '备注',
        'Select of Equipment Dept'  =>  '설비부문선택',
        'Equipment Type'    =>  '설비종류',
        'Tool Type'    =>  '부속종류',
        'Equipment'    =>  '설비',
        'Particular'    =>  '기술적특성',
        'Parts'    =>  '부속',
        'Item_Cn'    =>  '项目(조문)',
        'Item_en'    =>  '项目(영문)',
        'Tech Particular' => '기술적특성',
        'ItemName_Cn' => '품명(조문)',
        'ItemName_en' => '품명(영문)',
        'PartNo'      => '부속번호',
        'Unit'        => '单位',
        'Qtty'        => '数量',
        'Special'     => '특성',
        'Dept'        => '부분',
        'Kind'        => '종류',
        'PartName_Cn' => '부속이름(조문)',
        'PartName_En' => '부속이름(영문)',
    ],

    "EquipmentTypeManage"  => [
        'type_Cn' => '종류(조문)',
        'type_en' => '종류(영문)',
        'description' => '说明',
        'Equipment_Cn' => '设备名称(조문)',
        'Equipment_en' => '设备名称(영문)',
        'Remark'    =>  '备注',
        'type'  =>  '종류'
    ],

    "IssaCode"  => [
        'Index' => '분류',
        'Index Code' => '분류번호',
        'Index Content' => '분류내용',
        'ISSA Code' => '코드',
        'Content' => '내용',
        'Content_Cn' => '내용(조문)',
        'Content_en' => '내용(영문)',
        'Special'    =>  '특성',
    ],

    "shipNameManage"  => [
        'RegNo' => '登记번호',
        'ShipName of Structure_Cn' => '기구직제船舶名称(조문)',
        'ShipName of Structure_en' => '기구직제船舶名称(영문)',
        'Persons' => '인원수',
    ],

	'register'      => [
		'register'            => '登记',
		''
	]

];