<?php
if(isset($excel)) $header = 'excel-header';
else $header = 'sidebar';
?>

<?php
$isHolder = Session::get('IS_HOLDER');
$ships = Session::get('shipList');
?>

@extends('layout.'.$header)

@section('content')

    @if(!isset($excel))

        <div class="main-content">
            <style>
                .filter_row {
                    background-color: #45f7ef;
                }
                .chosen-drop {
                    width : 350px !important;
                }
            </style>
            <div class="page-content">
                <div class="page-header">
                    <div class="col-md-3">
                        <h4>
                            <b>船舶证书</b>
                            <small>
                                <i class="icon-double-angle-right"></i>
                                船舶证书目录
                            </small>
                        </h4>
                    </div>
                    @if(isset($shipName['name']))
                        <div class="col-md-6 center" style="font-size: 16px">
                            <strong>【&nbsp;{{$shipName['name']}}({{$shipName['shipName_Cn']}})&nbsp;】号 &nbsp; 船舶证书目录 </strong>
                        </div>
                    @endif
                </div>
                <div class="col-md-12">
                    <div class="row">
                        <div class="col-md-2">
                            <label class="control-label no-padding-right" style="float: left;padding-top:5px">船名</label>
                            <div class="col-sm-9 no-padding-right" >
                                <select class="form-control" id="select-ship">
                                    <option value="" @if(!isset($shipId)) selected @endif>全体</option>
                                    @foreach($shipList as $ship)
                                        <option value="{{ $ship['RegNo'] }}"
                                                @if(isset($shipId) && ($shipId == $ship['RegNo'])) selected @endif>{{$ship['shipName_Cn'].' | '.$ship['shipName_En']}}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-md-2 no-padding">
                            <label class="no-padding-right" style="float: left;padding-top:5px">证书名</label>
                            <input type="text" class="form-control" id="certName" style="float:left;margin-left: 10px;width:70%" value="@if(isset($certName)){{$certName}}@endif">
                        </div>
                        <div class="col-md-2" style="width:22%">
                            <label class="no-padding-right" style="float: left;padding-top:5px">签发部门</label>
                            <input type="text" class="form-control" id="issuUnit" style="float:left;margin-left: 10px;width:70%" value="@if(isset($issuUnit)){{$issuUnit}}@endif">
                        </div>
                        <div class="col-md-2">
                            <label class="control-label no-padding-right" style="float: left;padding-top:7px">到期前</label>
                            <div class="col-sm-7">
                                <select class="form-control select-expire" id="select-expire">
                                    <option value=""></option>
                                    @for($month = 1; $month < 17; $month++)
                                        @if($month < 13)
                                            <option value="{{$month}}" @if(isset($expireMonth) && ($expireMonth == $month)) selected @endif>{{$month}} 个月</option>
                                        @else
                                            <option value="{{$month}}" @if(isset($expireMonth) && ($expireMonth == $month)) selected @endif>{{$month - 12}} 年</option>
                                        @endif
                                    @endfor
                                </select>
                            </div>
                        </div>
                        <button class="btn btn-primary btn-sm search-btn" style="float:left; width :80px"><i class="icon-search"></i>搜索</button>
                        <button class="btn btn-warning btn-sm excel-btn" style="float:left;margin-left: 5px;width :80px;"><i class="icon-table"></i><b>{{ trans('common.label.excel') }}</b></button>
                        <div class="col-lg-1" style="float: right;margin-right:30px">
                            @if(!$isHolder)
                                @if(isset($shipId))
                                    <button class="btn btn-sm btn-primary" onclick="modifyCertItem('{{$shipId}}', 0)" style="width: 80px">
                                        <i class="icon-plus-sign-alt"></i>
                                        追加
                                    </button>
                                @endif
                            @endif
                            <div id="dialog-modify-cert" class="hide">
                            </div>
                            <!-- #dialog-message -->
                        </div>
                    </div>
                    <div class="row">
                        <div class="space-10"></div>
                        <div style="overflow-y: scroll; width: 100%">
                            @else
                                @include('layout.excel-style')
                            @endif
                            <table class="table table-striped table-bordered table-hover" @if(!isset($excel))style="width: 100%;margin-bottom: 0px !important;"@endif>
                                <thead>
                                <tr class="black br-hblue">
                                    <th class="center" style="width:4%;word-break: break-all;">{{ transShipManager('shipCertlist.No') }}</th>
                                    <th class="center" style="width:5%;word-break: break-all;">{{ transShipManager('shipCertlist.ShipName') }}</th>
                                    <th class="center" style="width:8%;word-break: break-all;">{{ transShipManager('shipCertlist.EnglishName') }}</th>
                                    <th class="center" style="width:10%;word-break: break-all;">{{ transShipManager('CertManage.RefNo') }}</th>
                                    <th class="center" style="width:20%;word-break: break-all;">{{ transShipManager('shipCertlist.CertName') }}</th>
                                    <th class="center" style="width:5%;word-break: break-all;">{{ transShipManager('shipCertlist.Kind') }}</th>
                                    <th class="center" style="width:12%;word-break: break-all;">{{ transShipManager('shipCertlist.Issuing Authoriy') }}</th>
                                    <th class="center" style="width:6%;word-break: break-all;">{{ transShipManager('shipCertlist.RegStatus') }}</th>
                                    <th class="center" style="width:8%;word-break: break-all;">{{ transShipManager('shipCertlist.Date of Expiry') }}</th>
                                    <th class="center" style="width:8%;word-break: break-all;">{{ transShipManager('shipCertlist.Date of Issue') }}</th>
                                    @if(!$isHolder)
                                        @if(!isset($excel))
                                            <th class="center" style="width:3%">{{ transShipManager('shipCertlist.Scan') }}</th>
                                            <th class="center" style="width:6%"></th>
                                        @endif
                                    @endif
                                </tr>
                                </thead>
                                @if(!isset($excel))
                            </table>
                        </div>
                        <div id="div_contents" style="overflow-x:hidden; overflow-y:scroll; width:100%; height:67vh; border-bottom: 1px solid #eee">
                            <table class="table table-bordered table-hover" id="ship_cert_table">
                                @endif
                                <tbody id="ship_cert_body">
								<?php $index = 1; ?>
                                @foreach($list as $cert)
                                    @if(!empty($expireMonth))
                                        <tr class="filter_row">
                                    @else
                                        <tr class="">
                                            @endif
                                            <td class="center" rowId="{{$cert['id']}}" style="width:4%">{{$index++}}</td>
                                            <td class="center" style="width:5%;word-break: break-all;">{{$cert['shipName_Cn']}}</td>
                                            <td class="center" style="width:8%;word-break: break-all;">{{$cert['shipName_En']}}</td>
                                            <td style="width:10%;word-break: break-all;">{{$cert['CertNo']}}</td>
                                            <td style="width:20%;word-break: break-all;">{{$cert['CertName_Cn']}}</td>
                                            <td class="center" style="width:5%;word-break: break-all;">{{$cert['CertKind']}}</td>
                                            <td class="center" style="width:12%;word-break: break-all;">{{$cert['IssuedAdmin_Cn']}}</td>
                                            <td class="center" style="width:6%;word-break: break-all;">{{$cert['CertLevel']}}</td>
                                            <td class="center" style="width:8%;word-break: break-all;">{{$cert['IssuedDate']}}</td>
                                            <td class="center" style="width:8%;word-break: break-all;">{{$cert['ExpiredDate']}}</td>
                                            @if(!$isHolder)
                                                @if(!isset($excel))
                                                    <td class="center" style="width:3%">
                                                        @if(!empty($cert['Scan']))
															<?php
															$temp = explode('.', $cert['Scan']);
															$ext = '.'.end($temp);
															$filename = $cert['shipName_Cn'].'_'.$cert['CertName_Cn'].'_证书复本.'.$ext;
															?>
                                                            <a href="/fileDownload?type=ship-cert&path={{$cert['Scan']}}&filename={{$filename}}"
                                                               class="hide-option" title="证书复本" style="padding-top:6px">
                                                                <i class="blue icon-print"></i>
                                                            </a>
                                                        <!--
                                            <a href="{{url('uploads//'.$cert['Scan'])}}" data-rel="colorbox"><i class="blue icon-print"></i></a>
                                            -->
                                                        @endif
                                                    </td>
                                                    <td class="action-buttons center" style="width:6%">
                                                        <a class="blue" href="javascript:modifyCertItem('', '{{$cert['id']}}')">
                                                            <i class="icon-edit bigger-130"></i>
                                                        </a>

                                                        <a class="red" href="javascript:deleteCertItem({{$cert['id']}})">
                                                            <i class="icon-trash bigger-130"></i>
                                                        </a>
                                                    </td>
                                                @endif
                                            @endif
                                        </tr>
                                        @endforeach
                                </tbody>
                            </table>
                            @if(!isset($excel))
                        </div>
                    </div>
                </div>
            </div>
        </div>


        <script>
            var token = '{!! csrf_token() !!}';
            var shipName_Cn = '';
            @if(isset($shipName['name']))
                shipName_Cn = '{!! $shipName['name'] !!}号';
            @endif


            $(function () {


                $('.select-expire').on('change', function () {
                    var expireMonth = $(this).val() * 1;
                    var startDate = new Date();
                    var year = startDate.getFullYear();
                    var day = startDate.getDate();
                    var month = startDate.getMonth();

                    if(expireMonth < 13) {
                        month = month + expireMonth + 1;
                        if(month > 12) {
                            year++;
                            month = month - 12;
                        }

                        if(month < 10)
                            month = '0' + month;
                        if(day < 10)
                            day = '0' + day;
                    } else {
                        year = year + expireMonth - 12;
                    }
                    var expireDate = year + '-' + month + '-' + day;

                    year = startDate.getFullYear();
                    day = startDate.getDate();
                    month = startDate.getMonth() + 1;
                    if(month > 12) {
                        year++;
                        month = month - 12;
                    }

                    if(month < 10)
                        month = '0' + month;
                    if(day < 10)
                        day = '0' + day;
                    startDate = year + '-' + month + '-' + day;

                    var rows = $('#ship_cert_body').children();
                    var len = rows.length;
                    for(var row = 0;row<len;row++) {
                        var tr = rows.eq(row);
                        tr.removeClass('filter_row');
                        var dateStr = rows[row].children[9].innerHTML;
                        var issueDate = rows[row].children[8].innerHTML;
                        if((dateStr.length > 0 && dateStr < expireDate) || (issueDate.length < 1)) {
                            tr.addClass('filter_row');
                        }
                    }

                });


                $('.search-btn').on('click', function () {
                    var shipId = $('#select-ship').val();
                    var certName = $('#certName').val();
                    var issuUnit = $('#issuUnit').val();
                    var expireMonth = $('#select-expire').val();

                    var param = shipId.length > 0 ? '?shipId=' + shipId : '';
                    if(certName.length > 0)
                        param += param.length > 0 ? '&certName=' + certName : '?certName=' + certName;
                    if(issuUnit.length > 0)
                        param += param.length > 0 ? '&issuUnit=' + issuUnit : '?issuUnit=' + issuUnit;
                    if(expireMonth.length > 0)
                        param += param.length > 0 ? '&expireMonth=' + expireMonth : '?expireMonth=' + expireMonth;

                    location.href = 'shipCertList' + param;



                });

                $('.excel-btn').on('click', function () {
                    var shipId = $('#select-ship').val();
                    var certName = $('#certName').val();
                    var expireMonth = $('#select-expire').val();

                    var param = shipId.length > 0 ? '?shipId=' + shipId : '';
                    if(certName.length > 0)
                        param += param.length > 0 ? '&certName=' + certName : '?certName=' + certName;
                    if(expireMonth.length > 0)
                        param += param.length > 0 ? '&expireMonth=' + expireMonth : '?expireMonth=' + expireMonth;

                    location.href = 'shipCertListExcel' + param;
                });
            });

            function setDatePicker() {
                $('.date-picker').datepicker({autoclose: true}).next().on(ace.click_event, function () {
                    $(this).prev().focus();
                });
            }

            function setValidateForm() {
                $("#certUpdateForm").validate({
                    rules: {
                        IssuedAdmin_Cn: "required",
                        IssuedAdmin_En: "required",
                    },
                    messages: {
                        IssuedAdmin_Cn: "请输入签发部门(中文)。",
                        IssuedAdmin_En: "请输入签发部门(英文)。",
                    }
                });

            }

            function deleteCertItem(id) {
                var tableBody = document.getElementById('ship_cert_body');
                var rows = tableBody.children;
                var len = rows.length;
                var row = 0;
                for(;row<len;row++) {
                    var td = rows[row].children[0];
                    var certId = td.getAttribute('rowId');
                    if(id == certId)
                        break;
                }

                var tableRow = rows[row];
                var certName = tableRow.children[2].innerHTML;
                bootbox.confirm(certName + "  真要删掉吗?", function (result) {
                    if (result) {
                        $.post('deleteShipCert', {'_token':token, 'certId':id}, function (result) {
                            var code = parseInt(result);
                            if (code > 0) {
                                tableBody.deleteRow(row);
                            } else {
                                $.gritter.add({
                                    title: '错误',
                                    text: '['+ certName + ']' + ' 是已经被删掉的。',
                                    class_name: 'gritter-error '
                                });
                            }
                        });
                    }
                });

            }

            function bindCertPhoto() {
                $('#copy-photo').ace_file_input({
                    style: 'well',
                    btn_choose: '请选择复本文件。',
                    btn_change: null,
                    no_icon: 'icon-cloud-upload',
                    droppable: true,
                    thumbnail: 'small',//large | fit
                    preview_error: function (filename, error_code) {
                    }
                }).on('change', function () {
                });
            }

            function modifyCertItem(shipId, id) {
                $.post("getShipCertInfo", {'_token': token, 'shipId': shipId, 'certId': id}, function (data) {
                    if (data) {
                        $("#dialog-modify-cert").html(data);
                        $('[name="certName"]').val($('#certName').val());
                        $('[name="issuUnit"]').val($('#issuUnit').val());
                        $('[name="expireMonth"]').val($('#select-expire').val());
                        setDatePicker();
                        bindCertPhoto();
                        setValidateForm();
                        $('.chosen-select').chosen();

                        var title = shipName_Cn + ' 修改船舶证书';
                        if (id == 0)
                            title = shipName_Cn + ' 追加船舶证书'
                        var dialog = $("#dialog-modify-cert").removeClass('hide').dialog({
                            modal: true,
                            title: title,
                            title_html: true,
                            buttons: [
                                {
                                    text: "取消",
                                    "class": "btn btn-xs",
                                    click: function () {
                                        $(this).dialog("close");
                                    }
                                },
                                {
                                    text: "确认",
                                    "class": "btn btn-primary btn-xs",
                                    click: function () {
                                        $('#submit_btn').click();
                                    }
                                }
                            ]
                        });
                    }
                });
            }
        </script>
    @endif
@endsection