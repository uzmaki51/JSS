<?php
if(isset($excel)) $header = 'excel-header';
else $header = 'header';
?>

<?php
$isHolder = Session::get('IS_HOLDER');
?>

@extends('layout.'.$header)

@section('content')

    @if(!isset($excel))

        <div class="main-content">
            <style>
                table {
                    font-size: 12px;
                }
                .chosen-drop { width:260px !important;}
            </style>
            <div class="page-content">
                <div class="col-md-12">
                    <div class="row">
                        <form action="contract" method="get" class="form-search" id="excelForm">
                            <div class="col-md-3">
                                <label class="control-label no-padding-right" style="float: left;padding-top: 6px">{{ t('contract.ShipName', array(), 'operation') }}</label>
                                <div class="col-sm-9">
                                    <select class="form-control chosen-select" name="shipId">
                                        <option value="" @if(empty($shipId)) selected @endif>全部</option>
                                        @foreach($shipList as $ship)
                                            <option value="{{$ship['RegNo']}}"
                                                    @if(isset($shipId) && ($shipId == $ship['RegNo'])) selected @endif>{{$ship['shipName_Cn'] .' | ' .$ship['shipName_En']}}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <label class="control-label no-padding-right" style="float: left;padding-top: 6px">{{ t('contract.Contract Date', array(), 'operation') }}</label>
                                <div class="input-group col-sm-5" style="width:38%">
                                    <input class="form-control date-picker" name="fromDate" type="text"
                                           data-date-format="yyyy-mm-dd" value="{{$fromDate}}">
                                    <span class="input-group-addon"><i class="icon-calendar bigger-110"></i></span>
                                </div>
                                <label class="control-label no-padding-right" style="float: left;padding-top: 6px">~</label>
                                <div class="input-group col-sm-5" style="width:38%">
                                    <input class="form-control date-picker" name="toDate" type="text"
                                           data-date-format="yyyy-mm-dd" value="{{$toDate}}">
                                    <span class="input-group-addon"><i class="icon-calendar bigger-110"></i></span>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <label class="control-label no-padding-right" style="float: left;padding-top: 6px">{{ t('contract.Cargo', array(), 'operation') }}</label>
                                <div class="col-sm-9">
                                    <select class="form-control chosen-select" name="cargo">
                                        <option value="" @if(empty($cargoId)) selected @endif>全部</option>
                                        @foreach($cargoList as $cargo)
                                            <option value="{{$cargo['id']}}" @if($cargoId == $cargo['id']) selected @endif>{{$cargo['CARGO_En']}} | {{$cargo['CARGO_Cn']}}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <button type="submit" class="btn btn-primary btn-sm" style="float: left; width :80px"><i class="icon-search"></i>搜索</button>
                            <button class="btn btn-warning btn-sm excel-btn" style="float: left; margin-left: 8px; width :80px">
                                <i class="icon-table"></i><b>{{ trans('common.label.excel') }}</b>
                            </button>
                        </form>
                    </div>
                    <div class="space-4"></div>
                    <div class="row">
                        <div class="table-responsive" id="" style="overflow-x: auto;">
                            @else
                                @include('layout.excel-style')
                            @endif
                            <table class="table table-striped table-bordered table-hover data-table" style="margin-bottom: 0;font-size: 12px">
                                <thead>
                                <tr class="black br-hblue">
                                    <th>No</th>
                                    <th>{{ t('contract.Contract', array(), 'operation') }}</th>
                                    <th>{{ t('contract.ShipName', array(), 'operation') }}</th>
                                    <th>{{ t('contract.VoyNo', array(), 'operation') }}</th>
                                    <th>{{ t('contract.Contract Date', array(), 'operation') }}</th>
                                    <th>{{ t('contract.Cargo', array(), 'operation') }}</th>
                                    <th>{{ t('contract.Count', array(), 'operation') }}</th>
                                    <th>{{ t('contract.Unit', array(), 'operation') }}</th>
                                    <th>{{ t('contract.Lp', array(), 'operation') }}</th>
                                    <th>{{ t('contract.Dp', array(), 'operation') }}</th>
                                    <th colspan="2">{{ t('contract.LayCan', array(), 'operation') }}</th>
                                    <th>{{ t('contract.Lp Rate', array(), 'operation') }}</th>
                                    <th>{{ t('contract.Dp Rate', array(), 'operation') }}</th>
                                    <th>{{ t('contract.Freight', array(), 'operation') }}</th>
                                    <th>{{ t('contract.B/L Count', array(), 'operation') }}</th>
                                    <th>{{ t('contract.Demurrage', array(), 'operation') }}</th>
                                    <th>{{ t('contract.Brokerage', array(), 'operation') }}[%]</th>
                                    @if(!$isHolder)
                                        <th style="width:40px"></th>
                                    @endif
                                </tr>
                                </thead>
                                <tbody>
								<?php
								if(isset($excel)) $index = 1;
								else $index = ($list->currentPage() - 1) * $list->perPage() + 1;
								?>
                                @foreach($list as $item)
                                    <tr>
                                        <td style="text-align: center" data-id="{{$item->id}}">{{$index++}}</td>
                                        <td style="text-align: center" data-id="{{$item->CP_kind}}">{{ $item['typeName']['CP(English)'] }}</td>
                                        <td style="text-align: center" data-ship="{{$item->Ship_ID}}">@if($item->shipName){{$item->shipName->shipName_Cn }}@endif</td>
                                        <td style="text-align: center" data-id="{{ $item->Voy_No }} | {{ $item->CP_No }}" class="simple-text hide-option" title="{{ $item->Voy_No }} | {{ $item->CP_No }}">{{ $item->Voy_No }}</td>
                                        <td style="text-align: center">{{ convert_date($item->CP_Date) }}</td>
                                        <td style="text-align: center" data-id="{{$item->Cargo}}">{{ $item->carGoName() }}</td>
                                        <td data-limit="{{$item->Cgo_Qttylimit * 10}}" data-cargo="{{$item->Cgo_Qtty}}" style="text-align: right">{{ \App\Http\Controllers\Util::getNumberFt($item->Cgo_Qtty) }}</td>
                                        <td style="text-align: center" data-id="{{$item->Unit}}">{{$item->Unit}}</td>
                                        <td style="text-align: center" data-id="{{$item->LPort}}">
                                            {{ $item->lportName() }}
                                        </td>
                                        <td style="text-align: center" data-id="{{$item->DPort}}">
                                            {{ $item->dPortName() }}
                                        </td>
                                        <td style="text-align: center">{{ convert_date($item->LayCan_Date1) }}</td>
                                        <td style="text-align: center">{{ convert_date($item->LayCan_Date2) }}</td>
                                        <td style="text-align: center">{{ $item->L_Rate }}</td>
                                        <td style="text-align: center">{{ $item->D_Rate }}</td>
                                        <td style="text-align: right" data-val="{{$item->Freight}}">{{ \App\Http\Controllers\Util::getNumberFt($item->Freight) }}</td>
                                        <td style="text-align: right" data-val="{{$item->B_L}}">{{ \App\Http\Controllers\Util::getNumberFt($item->B_L) }}</td>
                                        <td style="text-align: right" data-val="{{$item->Demurrage}}">{{ \App\Http\Controllers\Util::getNumberFt($item->Demurrage) }}</td>
                                        <td style="text-align: right" data-val="{{$item->Brokerage}}">{{ \App\Http\Controllers\Util::getNumberFt($item->Brokerage) }}</td>
                                        @if(!$isHolder)
                                            <td class="action-buttons">
                                                <a href="javascript:void(0);" class="red row_trash_btn"><i class="icon-trash bigger-130"></i></a>
                                            </td>
                                        @endif
                                    </tr>
                                @endforeach
                                @if(!isset($excel))
                                    <tr>
                                        <td data-id="">{{$index}}</td>
                                        <td colspan="18">新添加</td>
                                    </tr>
                                @endif
                                </tbody>
                            </table>
                            @if(!isset($excel))
                        </div>
                        {!! $list->render() !!}
                    </div>
                    <div class="space-10"></div>
                    @if(!$isHolder)
                        <div class="row" >
                            <div class="input">
                                <form action="{{ action('Operation\OperationController@updateContract') }}" method="POST" id="constract-form">
                                    <input type="hidden" name="_token" value="{{csrf_token()}}">
                                    <input type="hidden" name="voyId" value="">
                                    <table class="table table-striped table-bordered table-hover" >
                                        <thead>
                                        <tr class="black br-hblue">
                                            <th style="width:10%">{{ t('contract.Contract', array(), 'operation') }}</th>
                                            <th style="width: 10%;">{{ t('contract.ShipName', array(), 'operation') }}</th>
                                            <th style="width: 15%;white-space: nowrap">{{ t('contract.VoyNo', array(), 'operation') }}</th>
                                            <th style="width:15%">{{ t('contract.Contract Date', array(), 'operation') }}</th>
                                            <th style="width: 10%;">{{ t('contract.Cargo', array(), 'operation') }}</th>
                                            <th style="width: 20%;" id="Voy_Qtty" colspan="2">{{ t('contract.Count', array(), 'operation') }}</th>
                                            <th>{{ t('contract.Unit', array(), 'operation') }}</th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        <tr>
                                            <td>
                                                <select class="form-control" name="CP_Kind" onchange="Voy_change(this.value)">
                                                    <option value="1">Voy | 航次租船</option>
                                                    <option value="2">TC | 期租船</option>
                                                </select>
                                            </td>
                                            <td>
                                                <div style="width: 100%">
                                                    <select class="form-control chosen-select" name="Ship_ID">
                                                        <option value="">&nbsp;</option>
                                                        @foreach($shipList as $ship)
															<?php if(empty($ship['name'])) continue; ?>
                                                            <option value="{{$ship['RegNo']}}" @if($shipId == $ship['RegNo']) selected @endif>
                                                                {{ $ship['shipName_En'] }}&nbsp;|&nbsp;{{$ship['shipName_Cn']}}
                                                            </option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                            </td>
                                            <td><input type="text" class="form-control" name="Voy_No" style="float: left;width: 20%;">
                                                <label style="float:left;padding-top:4px;padding-left:3px"> | </label>
                                                <input type="text" class="form-control" name="CP_No" style="float: left;width: 60%;"></td>
                                            <td>
                                                <div class="input-group">
                                                    <input class="form-control date-picker" id="" name="CP_Date" type="text" data-date-format="yyyy-mm-dd" value="">
                                                    <span class="input-group-addon"><i class="icon-calendar bigger-110"></i></span>
                                                </div>
                                            </td>
                                            <td>
                                                <div>
                                                    <select multiple="multiple" class="width-80 chosen-select" id="cargo_sel" name="Cargo[]" data-placeholder="请选择货物名。">
                                                        @foreach($cargoList as $cargo)
                                                            <option value="{{ $cargo->id }}">{{ $cargo->CARGO_En }} | {{ $cargo->CARGO_Cn }}</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                            </td>
                                            <td style="width: 40%;white-space: nowrap" colspan="2">
                                                <input type="number" class="form-control" name="Cgo_Qtty" style="float:left;width:50%" onchange="CalcFreight()">
                                                <label style="float:left;padding-top:4px;padding-left:3px">+/_</label>
                                                <input type="number" class="form-control" name="Cgo_Qttylimit" style="float:left;width:30%">
                                                <label style="float:left;padding-top:4px;padding-left:3px">%</label>
                                            </td>
                                            <td><input type="text" class="form-control" name="Unit"></td>
                                        </tr>
                                        </tbody>
                                        <thead>
                                        <tr class="black br-hblue">
                                            <th for="port1">{{ t('contract.Lp', array(), 'operation') }}</th>
                                            <th for="port2">{{ t('contract.Dp', array(), 'operation') }}</th>
                                            <th colspan="2">{{ t('contract.LayCan', array(), 'operation') }}</th>
                                            <th style="width: 10%;">{{ t('contract.Lp Rate', array(), 'operation') }}</th>
                                            <th style="width: 10%;">{{ t('contract.Dp Rate', array(), 'operation') }}</th>
                                            <th style="width: 15%;" id="Voy_Freight">{{ t('contract.Freight', array(), 'operation') }}</th>
                                            <th>{{ t('contract.totalFreight', array(), 'operation') }}</th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        <tr>
                                            <td>
                                                <div>
                                                    <select multiple="multiple" class="width-80 chosen-select" id="port1" name="LPort[]" data-placeholder="请选择港口。">
                                                        @foreach($portList as $port)
                                                            <option style="text-align: left;" value="{{ $port->id }}">{{ $port->Port_Cn."|".$port->Port_En }}</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                            </td>
                                            <td>
                                                <div>
                                                    <select multiple="multiple" class="width-80 chosen-select" id="port2" name="DPort[]" data-placeholder="请选择港口。">
                                                        @foreach($portList as $port)
                                                            <option value="{{ $port->id }}">{{ $port->Port_Cn."|".$port->Port_En }}</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                            </td>
                                            <td>
                                                <div class="input-group">
                                                    <input class="form-control date-picker" name="LayCan_Date1" type="text" data-date-format="yyyy-mm-dd">
                                                    <span class="input-group-addon"><i class="icon-calendar bigger-110"></i></span>
                                                </div>
                                            </td>
                                            <td><div class="input-group">
                                                    <input class="form-control date-picker" name="LayCan_Date2" type="text" data-date-format="yyyy-mm-dd">
                                                    <span class="input-group-addon"><i class="icon-calendar bigger-110"></i></span>
                                                </div>
                                            </td>
                                            <td><input class="form-control" name="L_Rate" id="lrate-complex"></td>
                                            <td><input class="form-control" name="D_Rate" id="drate-complex"></td>
                                            <td><input type="text" class="form-control" name="Freight" onchange="javascript:CalcFreight()"></td>
                                            <td><input type="text" class="form-control" name="total_Freight"></td>
                                        </tr>
                                        </tbody>
                                        <thead>
                                        <tr class="black br-hblue">
                                            <th>{{ t('contract.B/L Count', array(), 'operation') }}</th>
                                            <th>{{ t('contract.Demurrage', array(), 'operation') }}</th>
                                            <th>{{ t('contract.Brokerage', array(), 'operation') }}[%]</th>
                                            <th>{{ t('contract.Charterer', array(), 'operation') }}</th>
                                            <th>{{ t('contract.Shipper', array(), 'operation') }}</th>
                                            <th>{{ t('contract.Consignee', array(), 'operation') }}</th>
                                            <th>{{ t('contract.Remark', array(), 'operation') }}</th>
                                            @if(!$isHolder)
                                                <th><button type="submit" class="btn btn-sm btn-primary no-radius" id="btn-modify-contract" style="width: 80px"><i class="icon-save"></i>登记</button></th>
                                            @endif
                                        </tr>
                                        </thead>
                                        <tbody>
                                        <tr>
                                            <td><input type="text" class="form-control" name="B_L"></td>
                                            <td><input type="text" class="form-control" name="Demurrage"></td>
                                            <td><input type="text" class="form-control" name="Brokerage"></td>
                                            <td><input type="text" class="form-control" name="Charterer"></td>
                                            <td><input type="text" class="form-control" name="Shipper"></td>
                                            <td><input type="text" class="form-control" name="Consignee"></td>
                                            <td colspan="2"><input type="text" class="form-control" name="Remarks"></td>
                                        </tr>
                                        </tbody>
                                    </table>
                                </form>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <script>


            var token = '<?php echo csrf_token() ?>';
            var state = '{!! $status !!}';

            var lData = <?php echo '['; foreach ($lrate as $late) {
					echo '{ label : "'.$late['L_Rate'].'", category:"L_RATE"},';
				} echo '];';
				?>
            var dData = <?php echo '['; foreach ($drate as $late) {
					echo '{ label : "'.$late['D_Rate'].'", category:"D_RATE"},';
				} echo '];';
				?>


                $(function () {

                    if(state == 'error') {
                        $.gritter.add({
                            title: '错误',
                            text: '船名和航次号码重复了。',
                            class_name: 'gritter-error'
                        });
                    } else if(state == 'success') {
                        $.gritter.add({
                            title: '通知',
                            text: '保存成功!',
                            class_name: 'gritter-success'
                        });
                    }

                    $('.btn-init').on('click', function () {
                        location.href = 'contract';
                    });

                    $('.excel-btn').on('click', function(){
                        event.preventDefault();
                        $('#excelForm').attr('action', 'contractExcel');
                        $('#excelForm').submit();
                    });

                    $('.data-table tr').on('click', function(){
                        $(this).closest('tbody').find('tr').removeClass('table-row-selected');
                        $(this).addClass('table-row-selected');

                        var obj = $(this).children();
                        var voyId = obj.eq(0).data('id');
                        var CP_Kind = obj.eq(1).data('id');

                        $("#cargo_sel").chosen('destroy');
                        $("#port1").chosen('destroy');
                        $("#port2").chosen('destroy');

                        $("[name=voyId]").val(voyId);
                        $("[name=CP_Kind]").val(CP_Kind);
                        Voy_change(CP_Kind);
                        $("[name=Ship_ID]").val(obj.eq(2).data('ship'));
                        var Voy_no = obj.eq(3).data("id") === undefined ? " | " :obj.eq(3).data("id");
                        var CP_no = Voy_no.split(' | ');
                        $("[name=Voy_No]").val(CP_no[0]);
                        $("[name=CP_No]").val(CP_no[1]);
                        $("[name=CP_Date]").val(obj.eq(4).text());

                        var cargo = String(obj.eq(5).data('id'));
                        if(cargo.indexOf(',') > -1)
                            cargo = cargo.substr(1,cargo.length - 2);
                        var cargoList = cargo.split(',');
                        $("#cargo_sel").val(cargoList);

                        $("[name=Cgo_Qtty]").val(obj.eq(6).data('cargo'));
                        $("[name=Cgo_Qttylimit]").val(obj.eq(6).data('limit'));
                        var Unit = String(obj.eq(7).data('id'));
                        $("[name=Unit]").val(Unit);
                        var Lport = String(obj.eq(8).data('id')).split(',');
                        $("#port1").val(Lport);
                        var Dport = String(obj.eq(9).data('id')).split(',');
                        $("#port2").val(Dport);
                        $("[name=LayCan_Date1]").val(obj.eq(10).text());
                        $("[name=LayCan_Date2]").val(obj.eq(11).text());
                        $("[name=L_Rate]").val(obj.eq(12).text());
                        $("[name=D_Rate]").val(obj.eq(13).text());
                        $("[name=Freight]").val(obj.eq(14).data('val'));
                        $("[name=B_L]").val(obj.eq(15).data('val'));
                        $("[name=Demurrage]").val(obj.eq(16).data('val'));
                        $("[name=Brokerage]").val(obj.eq(17).data('val'));

                        $("#cargo_sel").chosen();
                        $("#port1").chosen();
                        $("#port2").chosen();

                        $.ajax({
                            type: "GET",
                            url: '{{ url('/operation/getContract') }}',
                            data: {
                                voyId: voyId
                            }, success: function(data) {
                                $('input[name="total_Freight"]').val(data.total_Freight);
                                $('input[name="Charterer"]').val(data.Charterer);
                                $('input[name="Shipper"]').val(data.Shipper);
                                $('input[name="Consignee"]').val(data.Consignee);
                                $('input[name="Remarks"]').val(data.Remarks);
                            }
                        });
                    });

                    $.widget( "custom.catcomplete", $.ui.autocomplete, {
                        _renderMenu: function( ul, items ) {
                            var that = this,
                                currentCategory = "";
                            $.each( items, function( index, item ) {
                                if ( item.category != currentCategory ) {
                                    ul.append( "<li class='ui-autocomplete-category'>" + item.category + "</li>" );
                                    currentCategory = item.category;
                                }
                                that._renderItemData( ul, item );
                            });
                        }
                    });

                    $( "#lrate-complex" ).catcomplete({
                        delay: 0,
                        source: lData
                    });

                    $( "#drate-complex" ).catcomplete({
                        delay: 0,
                        source: dData
                    });

                    $("#constract-form").validate({
                        rules: {
                            Ship_ID : 'required',
                            Voy_No: "required",
                            CP_Date: "required",
                            Cargo: "required",
                            Cgo_Qtty: "required",
                            LPort: "required",
                            DPort: "required",
                        },
                        messages: {
                            Ship_ID : '请选择船名称。',
                            Voy_No: '请输入航次号码。',
                            CP_Date: '请输入合约日期。',
                            Cargo: '请选择货物名。.',
                            Cgo_Qtty: '请输入数量。',
                            LPort: "请选择上船港口。",
                            DPort: "请选者下船港口。",
                        }
                    });

                    // remove a row
                    $('.row_trash_btn').on('click', function(){
                        var trObj = $(this).closest('tr');
                        var obj = trObj.children();
                        var voyId = obj.eq(0).data('id');
                        var shipName = obj.eq(2).text();
                        var voyNo = obj.eq(3).text();

                        bootbox.confirm("[" + shipName + "]号的 " + voyNo + "航次租船合同真要删除吗?", function(result) {
                            if(result) {
                                // save in db
                                $.post("removeContract", {'_token': token, 'voyId': voyId}, function (data) {
                                    var result = jQuery.parseJSON(data);
                                    if (result.status == 'success') {
                                        trObj.remove();
                                    }
                                });
                            }
                        });
                    });
                });

            function CalcFreight() {
                var Cgo_Qtty = $('input[name="Cgo_Qtty"]').val();
                var Freight = $('input[name="Freight"]').val();
                var total = Cgo_Qtty * Freight;
                $('input[name="total_Freight"]').val(total);
            }

            function Voy_change(value) {
                if(value == 1) {
                    $('#Voy_Qtty').text('{{t('contract.Count', array(), 'operation')}}');
                    $('#Voy_Freight').text('{{t('contract.Freight', array(), 'operation')}}');
                } else {
                    $('#Voy_Qtty').text('{{t('contract.Applying', array(), 'operation')}}');
                    $('#Voy_Freight').text('{{t('contract.Hire', array(), 'operation')}}');
                }
            }
        </script>
    @endif
@stop