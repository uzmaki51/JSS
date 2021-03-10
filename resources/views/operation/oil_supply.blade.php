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
        <div class="page-content">
            <div class="page-header">
                <div class="col-md-6">
                    <h5><b>供给原油</b></h5>
                </div>
                <div class="col-md-6">
                    <a href="oilSupplyExcel" class="btn btn-warning btn-sm" style="float: right; width :80px"><i class="icon-table"></i><b>{{ trans('common.label.excel') }}</b></a>
                </div>
            </div>
            <div class="col-md-6" style="margin-top: 8px">
                <div class="row">
@else
    @include('layout.excel-style')
@endif
                    <h5><b>年별 공급정형</b></h5>
                    <table id="yearly" class="arc-std-table table table-striped table-bordered table-hover">
                        <thead>
                        <tr class="black br-hblue">
                            <th width="15%">{{transShipOperation("oilSupply.Year")}}</th>
                            <th width="10%">{{transShipOperation("oilSupply.FO")}}(MT)</th>
                            <!-- <th width="10%">FO[$/MT]</th> -->
                            <th width="10%">{{transShipOperation("oilSupply.DO")}}(MT)</th>
                            <!-- <th width="10%">DO[$/MT]</th> -->
                            <th width="10%">{{transShipOperation("oilSupply.LO")}}(Kg)</th>
                            <!-- <th width="10%">LO[$/Kg]</th> -->
                        </tr>
                        </thead>
                        <tbody>
                        <?php
                        $allFO = 0; $allPriceFO = 0;
                        $allDO = 0; $allPriceDO = 0;
                        $allLO = 0; $allPriceLO = 0;
                        ?>
                        @foreach($allData as $list)
                            <tr>
                                <td>{{ $list->SupplyYear }}</td>
                                <td style="text-align: right">{{ \App\Http\Controllers\Util::getNumberFt($list->SumFO) }}</td>
                                <!-- <td>{{ $list->SumPriceFO }}</td> -->
                                <td style="text-align: right">{{ \App\Http\Controllers\Util::getNumberFt($list->SumDO) }}</td>
                                <!-- <td>{{ $list->SumPriceDO }}</td> -->
                                <td style="text-align: right">{{ \App\Http\Controllers\Util::getNumberFt($list->SumLO) }}</td>
                                <!-- <td>{{ $list->SumPriceLO }}</td> -->
                            </tr>
                            <?php
                            $allFO += $list->SumFO; $allPriceFO += $list->SumPriceFO;
                            $allDO += $list->SumDO; $allPriceDO += $list->SumPriceDO;
                            $allLO += $list->SumLO; $allPriceLO += $list->SumPriceLO;
                            ?>
                        @endforeach
                        <tr>
                            <td style="text-align: center;font-weight: bold">{{transShipOperation("oilSupply.Total")}}</td>
                            <td style="text-align: right;font-weight: bold">{{ \App\Http\Controllers\Util::getNumberFt($allFO) }}</td>
                            <!-- <td>{{ $allPriceFO }}</td> -->
                            <td style="text-align: right;font-weight: bold">{{ \App\Http\Controllers\Util::getNumberFt($allDO) }}</td>
                            <!-- <td>{{ $allPriceDO }}</td> -->
                            <td style="text-align: right;font-weight: bold">{{ \App\Http\Controllers\Util::getNumberFt($allLO) }}</td>
                            <!-- <td>{{ $allPriceLO }}</td> -->
                        </tr>
                        </tbody>
                    </table>

                    <h5>
@if(!isset($excel))
                        <select name="selectYears" class="selectYearCtrl">
                            @foreach($yearList as $list)
                                <option value="{{ $list->Yearly }}" {{ $year == $list->Yearly ? 'selected' : '' }}>{{ $list->Yearly }}</option>
                            @endforeach
                        </select>
                        <b>年</b>&nbsp;&nbsp;
                        <b>按船供给情况</b></h5>
@else
    <br>
    <div>{{$year}}年 按船供给情况</div>
    <br>
@endif
                    <table id="shiply" class="arc-std-table table table-striped table-bordered table-hover">
                        <thead>
                        <tr class="black br-hblue">
                            <th width="15%">{{transShipOperation("oilSupply.ShipName")}}</th>
                            <th width="10%">{{transShipOperation("oilSupply.FO")}}(MT)</th>
                            <!-- <th width="10%">FO[$/MT]</th> -->
                            <th width="10%">{{transShipOperation("oilSupply.DO")}}(MT)</th>
                            <!-- <th width="10%">DO[$/MT]</th> -->
                            <th width="10%">{{transShipOperation("oilSupply.LO")}}(Kg)</th>
                            <!-- <th width="10%">LO[$/Kg]</th> -->
                        </tr>
                        </thead>
                        <tbody>
                        <?php
                        $allFO = 0; $allPriceFO = 0;
                        $allDO = 0; $allPriceDO = 0;
                        $allLO = 0; $allPriceLO = 0;
                        ?>
                        @foreach($yearData as $list)
                            <tr>
                                <td data-id="{{ $list->RegNo }}">{{ $list->shipName_Cn }}</td>
                                <td style="text-align: right">{{ \App\Http\Controllers\Util::getNumberFt($list->SHIPYEAR_FO) }}</td>
                                <!-- <td>{{ $list->SHIPYEAR_PRICE_FO }}</td> -->
                                <td style="text-align: right">{{ \App\Http\Controllers\Util::getNumberFt($list->SHIPYEAR_DO) }}</td>
                                <!-- <td>{{ $list->SHIPYEAR_PRICE_DO }}</td> -->
                                <td style="text-align: right">{{ \App\Http\Controllers\Util::getNumberFt($list->SHIPYEAR_LO) }}</td>
                                <!-- <td>{{ $list->SHIPYEAR_PRICE_LO }}</td> -->
                            </tr>
                            <?php
                            $allFO += $list->SHIPYEAR_FO; $allPriceFO += $list->SHIPYEAR_PRICE_FO;
                            $allDO += $list->SHIPYEAR_DO; $allPriceDO += $list->SHIPYEAR_PRICE_DO;
                            $allLO += $list->SHIPYEAR_LO; $allPriceLO += $list->SHIPYEAR_PRICE_LO;
                            ?>
                        @endforeach
                        <tr>
                            <td style="text-align: center;font-weight: bold">{{transShipOperation("oilSupply.Total")}}</td>
                            <td style="text-align: right;font-weight: bold">{{ \App\Http\Controllers\Util::getNumberFt($allFO) }}</td>
                            <!-- <td>{{ $allPriceFO }}</td> -->
                            <td style="text-align: right;font-weight: bold">{{ \App\Http\Controllers\Util::getNumberFt($allDO) }}</td>
                            <!-- <td>{{ $allPriceDO }}</td> -->
                            <td style="text-align: right;font-weight: bold">{{ \App\Http\Controllers\Util::getNumberFt($allLO) }}</td>
                            <!-- <td>{{ $allPriceLO }}</td> -->
                        </tr>
                        </tbody>
                    </table>
@if(!isset($excel))
                </div>
            </div>

            <div class="col-md-6">
                <h5>
                    <select name="selectYears" class="selectYearCtrl">
                        @foreach($yearList as $list)
                            <option value="{{ $list->Yearly }}" {{ $year == $list->Yearly ? 'selected' : '' }}>{{ $list->Yearly }}</option>
                        @endforeach
                    </select>
                    <b>年</b>&nbsp;&nbsp;
                    <select class="selectShipCtrl">
                        @foreach($shipList as $ship)
                            @if(!$isHolder)
                                <option value="{{ $ship->RegNo }}"  {{ $shipID == $ship->RegNo ? 'selected' : '' }}>{{ $ship->shipName_Cn." | ".$ship->shipName_En}} </option>
                            @elseif(in_array($ship->shipID, $ships))
                                <option value="{{ $ship->RegNo }}"  {{ $shipID == $ship->RegNo ? 'selected' : '' }}>{{ $ship->shipName_Cn." | ".$ship->shipName_En}} </option>
                            @endif
                        @endforeach
                    </select>号的供给情况
                </h5>
@else
    <?php
        foreach($shipList as $ship){
            if($shipID == $ship->RegNo) {
                if(!empty($ship->name))
                    $shipName = $ship->shipName_Cn.' | '.$ship['name'];
                else
                    $shipName = $ship->shipName_Cn;
            }
        }
    ?>
    <br>
    <div>{{ $year }}年 {{ $shipName }}号的供给情况</div>
    <br>
@endif
                <script src="{{ asset('/assets/js/overlib.js') }}"></script>
                <table class="table table-striped table-bordered table-hover">
                    <thead>
                    <tr class="black br-hblue">
                        <th width="15%" rowspan="2">{{transShipOperation("oilSupply.Date")}}</th>
                        <th width="20%" colspan="2">{{transShipOperation("oilSupply.FO")}}</th>
                        <th width="20%" colspan="2">{{transShipOperation("oilSupply.DO")}}</th>
                        <th width="20%" colspan="2">{{transShipOperation("oilSupply.LO")}}</th>
                    </tr>
                    <tr class="black br-hblue">
                        <th width="10%">{{transShipOperation("oilSupply.Qtty")}}(MT)</th>
                        <th width="10%">{{transShipOperation("oilSupply.Price")}}[$/MT]</th>
                        <th width="10%">{{transShipOperation("oilSupply.Qtty")}}(MT)</th>
                        <th width="10%">{{transShipOperation("oilSupply.Price")}}[$/MT]</th>
                        <th width="10%">{{transShipOperation("oilSupply.Qtty")}}(Kg)</th>
                        <th width="10%">{{transShipOperation("oilSupply.Price")}}[$/Kg]</th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php
                    $allFO = 0; $allPriceFO = 0;
                    $allDO = 0; $allPriceDO = 0;
                    $allLO = 0; $allPriceLO = 0;
                    ?>
                    @foreach($shipData as $list)
                        <tr>
                            <td class="invoice-detail" data-voy="{{$list->Voy_No}}" data-shipname="{{$list->shipName_Cn}}" data-discription="{{$list->Discription}}" data-refno="{{$list->Ref_No}}">
                                <a target="_blank" href="import?shipId={{$shipID}}&firstVoy={{$list->Voy}}&endVoy={{$list->Voy}}">{{ convert_date($list->SUPPLD_DATE) }}</a>
                            </td>
                            <td class="right">@if($list->FO != 0) {{ $list->FO }} @endif</td>
                            <td class="right">@if($list->PriceFO != 0) {{ $list->PriceFO }} @endif</td>
                            <td class="right">@if($list->DO != 0) {{ $list->DO }} @endif</td>
                            <td class="right">@if($list->PriceDO != 0) {{ $list->PriceDO }} @endif</td>
                            <td class="right">@if($list->LO != 0) {{ $list->LO }} @endif</td>
                            <td class="right">@if($list->PriceLO != 0) {{ $list->PriceLO }} @endif</td>
                        </tr>
                        <?php
                        $allFO += $list->FO; $allPriceFO += $list->PriceFO;
                        $allDO += $list->DO; $allPriceDO += $list->PriceDO;
                        $allLO += $list->LO; $allPriceLO += $list->PriceLO;
                        ?>
                    @endforeach
                    <tr style="font-weight: bold;">
                        <td>{{transShipOperation("oilSupply.Total")}}</td>
                        <td class="right">{{ $allFO }}</td>
                        <td class="right">{{ $allPriceFO }}</td>
                        <td class="right">{{ $allDO }}</td>
                        <td class="right">{{ $allPriceDO }}</td>
                        <td class="right">{{ $allLO }}</td>
                        <td class="right">{{ $allPriceLO }}</td>
                    </tr>
                    </tbody>
                </table>
@if(!isset($excel))
            </div>
        </div>
    </div>

    <script>
        var token = '<?php echo csrf_token() ?>';

        jQuery(function(e){

            $('.selectYearCtrl').on('change', function(){
                var year = $(this).val();
                document.cookie="year="+year;
                location.reload();
            });
            $('.selectShipCtrl').on('change', function(){
                var ship = $(this).val();
                document.cookie="shipID="+ship;
                location.reload();
            });
            $('#yearly tbody tr td').click(function() {
                $(this).closest('tr').removeClass("table-row-selected");
                $(this).closest('tr').addClass('table-row-selected');
                var obj = $(this).closest('tr').children();
                document.cookie = "year="+obj.eq(0).text();
                location.href = location.href
            });
            $('#shiply tbody tr td').click(function() {
                $(this).closest('tr').removeClass("table-row-selected");
                $(this).closest('tr').addClass('table-row-selected');
                var obj = $(this).closest('tr').children();
                document.cookie = "shipID="+obj.eq(0).data('id');
                location.href = location.href
            });
            /**/
            $('.plan_row_modify_btn').on('click', function(){
                // replace content
                var obj = $(this).closest('tr').children();
                obj.eq(1).html("<input type='text' value='"+ obj.eq(1).text() +"'>");
                obj.eq(2).html("<input type='text' value='"+ obj.eq(2).text() +"'>");
                obj.eq(3).html("<input type='text' value='"+ obj.eq(3).text() +"'>");
                obj.eq(4).html("<textarea rows='1' value='"+ obj.eq(3).text() +"'>");
                //
                obj.eq(5).find('.plan_btns_default').hide();
                obj.eq(5).find('.plan_btns_apply').show();
            });
            $('.plan_row_cancel_btn').on('click', function(){
                // replace content to original value
                var obj = $(this).closest('tr').children();
                obj.eq(1).html( obj.eq(1).data('old') );
                obj.eq(2).html( obj.eq(2).data('old') );
                obj.eq(3).html( obj.eq(3).data('old') );
                obj.eq(4).html( obj.eq(4).data('old') );
                //
                obj.eq(5).find('.plan_btns_default').show();
                obj.eq(5).find('.plan_btns_apply').hide();
            });
            // update row data
            $('.plan_row_apply_btn').on('click', function(){
                // replace content to original value

                var obj = $(this).closest('tr').children();

                var shipID = obj.eq(0).data('id');
                var year = $('.selectYearCtrl').val();
                var income = obj.eq(1).find('input').val();
                var expense = obj.eq(2).find('input').val();
                var profit = obj.eq(3).find('input').val();
                var remark = obj.eq(4).find('textarea').val();
                obj.eq(1).html( income );
                obj.eq(2).html( expense );
                obj.eq(3).html( profit );
                obj.eq(4).html( remark );
                //
                obj.eq(5).find('.plan_btns_default').show();
                obj.eq(5).find('.plan_btns_apply').hide();

                // save in db
                $.post("operation/updateYearPlan", {'_token': token, 'year': year, 'shipID': shipID,'income': income, 'expense': expense, 'profit': profit, 'remark': remark
                }, function (data) {
                    var result = jQuery.parseJSON(data);
                    if (result.status == 'success') {

                    }
                });

            });
            // insert new row data
            $('#plan_add_btn').on('click', function(){

                var obj = $(this).closest('tr').children();

                var shipID = obj.eq(0).find('select').val();
                var year = $('.selectYearCtrl').val();
                var income = obj.eq(1).find('input').val();
                var expense = obj.eq(2).find('input').val();
                var profit = obj.eq(3).find('input').val();
                var remark = obj.eq(4).find('textarea').val();

                // save in db
                $.post("operation/insertYearPlan", {'_token': token, 'year': year, 'shipID': shipID,'income': income, 'expense': expense, 'profit': profit, 'remark': remark
                }, function (data) {
                    var result = jQuery.parseJSON(data);
                    if (result.status == 'success') {
                        location.reload();
                    }
                });

            });
            // remove a row
            $('.plan_row_trash_btn').on('click', function(){

                var obj = $(this).closest('tr').children();
                var shipID = obj.eq(0).data('id');
                var year = $('.selectYearCtrl').val();

                /*bootbox.confirm("선택한 행을 삭제하겠습니까?", function(result) {
                    if(result) {
                        // save in db
                        $.post("operation/removeYearPlan", {'_token': token, 'year': year, 'shipID': shipID
                        }, function (data) {
                            var result = jQuery.parseJSON(data);
                            if (result.status == 'success') {
                                location.reload();
                            }
                        });
                    }
                });*/
                location.replace(location.href+'&year='+year);
            });

            $("td.invoice-detail").on("mouseover", function(){
                $shipName = $(this).data("shipname");
                $voy = $(this).data("voy");
                $discription = $(this).data("discription");
                $refno = $(this).data("refno");
                $returnHtml = "<table style='font-size: 9pt;'>" +
                                "<tr style='background: #117fa4;color: white;'>" +
                                    "<td colspan='6' nowrap='nowrap' style='text-align: left;'>仔细资料</td>"+
                                "<tr>" +
                                "<tr style='background: #d4e7ff;'>" +
                                    "<td style='text-align: right;white-space: nowrap'>航次号码:<td>" +
                                    "<td style='text-align: left;white-space: nowrap'>"+$voy+"<td>" +
                                "</tr>" +
                                "<tr style='background: #d4e7ff;'>" +
                                    "<td style='text-align: right;white-space: nowrap'>穿名称:<td>" +
                                    "<td style='text-align: left;white-space: nowrap'>"+$shipName+"<td>" +
                                "</tr>" +
                                "<tr style='background: #d4e7ff;'>" +
                                    "<td style='text-align: right;white-space: nowrap'>支出内容:<td>" +
                                    "<td style='text-align: left;white-space: nowrap'>"+$discription+"<td>" +
                                "</tr>" +
                                "<tr style='background: #d4e7ff;'>" +
                                    "<td style='text-align: right;white-space: nowrap'>参考号码:<td>" +
                                    "<td style='text-align: left;white-space: nowrap'>"+$refno+"<td>" +
                                "</tr>" +
                                "</table>";
                return overlib($returnHtml);
            });
            $("td.invoice-detail").on("mouseout", function(){
                nd();
            });

        });
    </script>
@endif
@stop
