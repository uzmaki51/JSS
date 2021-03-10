<?php
if(isset($excel)) $header = 'excel-header';
else $header = 'sidebar';
?>
<?php
$isHolder = Session::get('IS_HOLDER');
$shipList = Session::get('shipList');
?>
@extends('layout.'.$header)

@section('content')

    @if(!isset($excel))

        <div class="main-content">
            <div class="page-content">
                <div class="page-header">
                    <div class="col-md-3">
                        <h4>
                            <b>{{ transShipManager("title.ShipCert") }}</b>
                            <small>
                                <i class="icon-double-angle-right"></i>
                                {{ transShipManager('title.CertKind') }}
                            </small>
                        </h4>
                    </div>
                </div>
                <div class="col-md-12">
                    <div class="row">
                        <div class="col-sm-4">
                            <div class="input-group">
                            <span class="input-icon" style="width:250px">
                            <input placeholder="请输入要搜索的证书名称。" type="text" class="form-control" id="search_cert" value="{{$cert}}">
                            <i class="icon-search nav-search-icon"></i>
                            </span>
                                <span class="input-group-btn">
                                <button class="btn btn-sm btn-primary no-radius" type="button" onclick="filterByCertKeyword()" style="width: 80px">
                                    <i class="icon-search"></i>{{ transShipManager('captions.search') }}
                                </button>
                                <button class="btn btn-sm btn-warning no-radius" type="button" onclick="filterByCertKeywordExcel()"
                                        style="margin-left: 5px; width :80px">
                                    <i class="icon-table"></i><b>{{ trans('common.label.excel') }}</b>
                                </button>
                            </span>
                            </div>
                        </div>
                        @if(!$isHolder)
                            <div class="col-md-1" style="float: right;text-align:right">
                                <button class="btn btn-sm btn-primary no-radius" onclick="modifyCertType(0)" style="width: 80px">
                                    <i class="icon-plus-sign-alt"></i>
                                    {{ transShipManager('captions.add') }}
                                </button>
                                <div id="dialog-modify-cert" class="hide">
                                </div>
                                <!-- #dialog-message -->
                            </div>
                        @endif
                    </div>
                    <div class="row">
                        <div class="space-4"></div>
                        <div style="overflow-y: scroll; width: 100%">
                            @else
                                @include('layout.excel-style')
                            @endif
                            <table class="table table-bordered table-striped table-hover arc-std-table">
                                <thead>
                                <tr class="black br-hblue">
                                    <th class="center" style="width:4%">{{ transShipManager('CertManage.No') }}</th>
                                    <th class="center" style="width:10%">{{ transShipManager('CertManage.RefNo') }}</th>
                                    <th class="center" style="width:22%">{{ transShipManager('CertManage.CertName_Cn') }}</th>
                                    <th class="center" style="width:27%">{{ transShipManager('CertManage.CertName_En') }}</th>
                                    <th class="center" style="width:7%">{{ transShipManager('CertManage.Kind') }}</th>
                                    <th class="center" style="width:22%">{{ transShipManager('CertManage.Description') }}</th>
                                    @if(!$isHolder)
                                        <th class="center" style="width:6%"></th>
                                    @endif
                                </tr>
                                </thead>
                                @if(!isset($excel))
                            </table>
                        </div>
                        <div id="div_contents" style="overflow-x:hidden; overflow-y:scroll; width:100%; height:67vh; border-bottom: 1px solid #eee">
                            <table class="table table-bordered table-striped table-hover" id="ship_cert_table">
                                @endif
                                <tbody id="ship_cert_body">
								<?php $index = 1; ?>
                                @foreach($list as $cert)
                                    <tr>
                                        <td class="center" rowId="{{$cert['id']}}" style="width:4%;word-break: break-all;">{{$index++}}</td>
                                        <td style="width:10%;word-break: break-all;">{{$cert['CertNo']}}</td>
                                        <td style="width:22%;word-break: break-all;">{{$cert['CertName_Cn']}}</td>
                                        <td style="width:27%;word-break: break-all;">{{$cert['CertName_En']}}</td>
                                        <td class="center" style="width:7%;word-break: break-all;">{{$cert['CertKind']}}</td>
                                        <td style="width:22%;word-break: break-all;">{{$cert['Details']}}</td>
                                        @if(!$isHolder)
                                            <td class="action-buttons" style="width:6%;word-break: break-all;">
                                                <a class="blue" href="javascript:modifyCertType({{$cert['id']}})">
                                                    <i class="icon-edit bigger-130"></i>
                                                </a>
                                                <a class="red" href="javascript:deleteCertType({{$cert['id']}})">
                                                    <i class="icon-trash bigger-130"></i>
                                                </a>
                                            </td>
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

            $(document).ready(function () {
                @if(isset($error))
                $.gritter.add({
                    title: '错误',
                    text: '{{$error}}',
                    class_name: 'gritter-error'
                });
                @endif
            });

            function filterByCertKeyword() {
                var keyword = $("#search_cert").val();
                var param = '';
                if(keyword.length > 0)
                    param = '?cert=' + keyword;
                location.href = 'shipCertManage' + param;
            }

            function filterByCertKeywordExcel() {
                var keyword = $("#search_cert").val();
                var param = '';
                if(keyword.length > 0)
                    param = '?cert=' + keyword;
                location.href = 'shipCertManageExcel' + param;
            }

            function setValidateForm() {
                $("#certUpdateForm").validate({
                    rules: {
                        CertNo : "required",
                        CertName_Cn: "required",
                        CertName_En: "required",
                    },
                    messages: {
                        CertNo : "请输入证书名称。",
                        CertName_Cn: "请输入证书名称(中文)",
                        CertName_En: "请输入证书名称(英文)",
                    }
                });
            }

            function deleteCertType(id) {
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
                var certName = tableRow.children[1].innerText;
                bootbox.confirm('['+ certName + ']  ' + "  真要删掉证书吗?", function (result) {
                    if (result) {
                        $.post('deleteShipCertType', {'_token':token, 'certId':id}, function (result) {
                            var code = parseInt(result);
                            if (code > 0) {
                                tableBody.deleteRow(row);
                            } else {
                                $.gritter.add({
                                    title: '错误',
                                    text: '['+ certName + ']' + ' 此证书已经被删掉了。',
                                    class_name: 'gritter-error '
                                });
                            }
                        });
                    }
                });

            }

            function modifyCertType(id) {
                $.post("getCertType", {'_token': token, 'certId': id}, function (data) {
                    if (data) {
                        $("#dialog-modify-cert").html(data);
                        $('[name="cert"]').val($('#search_cert').val());
                        $('.chosen-select').chosen();
                        setValidateForm();
                        var title = '修改证书';
                        if (id == 0)
                            title = '追加证书'
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