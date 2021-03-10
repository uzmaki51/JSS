@extends('layout.sidebar')

<?php
$isHolder = Session::get('IS_HOLDER');
?>

@section('content')
    <div class="main-content">
        <div class="page-content">

            <div class="col-md-12">
                <style>
                    h5 b span {padding:6px; border: 1px solid #eee; font-weight: 300}
                </style>
                <div class="col-md-8">
                    <div class="row">
                        <h5>
                            <select class="select_plan" id="select_year">
                                {{--@foreach($yearList as $list)--}}
                                {{--<option value="{{ $list->Yearly }}" {{ $year == $list->Yearly ? 'selected' : '' }}>{{ $list->Yearly }}</option>--}}
                                {{--@endforeach--}}
                                <option value="2013" {{ $year == 2013 ? 'selected' : '' }}>2013</option>
                                <option value="2014" {{ $year == 2014 ? 'selected' : '' }}>2014</option>
                                <option value="2015" {{ $year == 2015 ? 'selected' : '' }}>2015</option>
                                <option value="2016" {{ $year == 2016 ? 'selected' : '' }}>2016</option>
                                <option value="2017" {{ $year == 2017 ? 'selected' : '' }}>2017</option>
                                <option value="2018" {{ $year == 2018 ? 'selected' : '' }}>2018</option>
                                <option value="2019" {{ $year == 2019 ? 'selected' : '' }}>2019</option>
                                <option value="2020" {{ $year == 2020 ? 'selected' : '' }}>2020</option>
                                <option value="2021" {{ $year == 2021 ? 'selected' : '' }}>2021</option>
                            </select>
                            <b>{{ t('Year') }}</b>&nbsp;&nbsp;
                            <b>{{ t('Plan Year') }}</b>
                        </h5>
                        <table class="arc-std-table table table-striped table-bordered table-hover">
                            <thead>
                            <tr class="black br-hblue">
                                <th width="15%">{{ t('ShipName') }}</th>
                                <th width="10%" class="center">{{ t('Income') }}</th>
                                <th width="10%" class="center">{{ t('Expense') }}</th>
                                <th width="10%" class="center">{{ t('Profit') }}</th>
                                <th>{{ t('Remark') }}</th>
                                @if(!$isHolder)
                                    <th width="65px"></th>
                                @endif
                            </tr>
                            </thead>
                            <tbody id="plan-table">
							<?php $totalIncome = $totalExpense = $totalProfit = 0; ?>
                            @foreach($yearData as $list)
								<?php
								$totalIncome += $list->INCOME;
								$totalExpense += $list->EXPENSE;
								$totalProfit += $list->PROFIT;
								?>
                                <tr>
                                    <td style="text-align: left" data-id="{{$list->ShipID}}">{{$list->name}} | {{$list->shipName_Cn}}</td>
                                    <td style="text-align: right" data-value="{{$list->INCOME}}">{{ \App\Http\Controllers\Util::getNumberFtNZ($list->INCOME) }}</td>
                                    <td style="text-align: right" data-value="{{$list->EXPENSE}}">{{ \App\Http\Controllers\Util::getNumberFtNZ($list->EXPENSE) }}</td>
                                    <td style="text-align: right" data-value="{{$list->PROFIT}}">{{ \App\Http\Controllers\Util::getNumberFtNZ($list->PROFIT) }}</td>
                                    <td>{{$list->REMARK}}</td>
                                    <td class="action-buttons">
                                        <div class="plan_btns_default">
                                            <a href="javascript:void(0);" class="blue plan_modify"><i class="icon-edit bigger-130"></i></a>
                                            <a href="javascript:void(0);" class="red plan_trash"><i class="icon-trash bigger-130"></i></a>
                                        </div>
                                        <div class="plan_btns_apply" style="display: none;">
                                            <a href="javascript:void(0);" class="blue plan_apply"><i class="icon-save bigger-130"></i></a>
                                            <a href="javascript:void(0);" class="red plan_cancel"><i class="icon-remove bigger-130"></i></a>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                            @if(!empty($remainShipList))
                                <tr>
                                    <td>
                                        <div>
                                            <select class="plan_year" id="" value="" style="width:100%">
                                                @foreach($remainShipList as $key =>$list)
                                                    <option value="{{ $key  }}">{{ $list }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </td>
                                    <td><input type="number" class="plan_income" ></td>
                                    <td><input type="number" class="plan_expense" ></td>
                                    <td><input type="number" class="plan_profit" ></td>
                                    <td><textarea  rows="1" class="plan_remark" ></textarea></td>
                                    @if(!$isHolder)
                                        <td class="action-buttons">
                                            <a href="javascript:void(0);" class="red plan_add"><i class="icon-plus bigger-130"></i></a>
                                        </td>
                                    @endif
                                </tr>
                            @endif
                            </tbody>
                            <tfoot>
                            <tr>
                                <td style="text-align: center;font-weight: bold;">{{  t('Sum') }}</td>
                                <td class="income_sum" style="text-align: right;font-weight: bold">{{ \App\Http\Controllers\Util::getNumberFtNZ($totalIncome) }}</td>
                                <td class="expense_sum" style="text-align: right;font-weight: bold">{{ \App\Http\Controllers\Util::getNumberFtNZ($totalExpense) }}</td>
                                <td class="profit_sum" style="text-align: right;font-weight: bold">{{ \App\Http\Controllers\Util::getNumberFtNZ($totalProfit) }}</td>
                            </tr>
                            </tfoot>
                        </table>
                    </div>
                    <div class="row">
                        <h5>
                            <b>{{$year .' '.t('Year')}}&nbsp;&nbsp;</b>
                            <b>{{t('ShipName')}}:</b>
                            <select class="select_plan" id="select_ship">
                                @foreach($shipList as $ship)
                                    <option value="{{ $ship->RegNo }}"  {{ $shipID == $ship->RegNo ? 'selected' : '' }}>@if(!empty($ship['name'])) {{$ship['name']}} |  @endif {{ $ship->shipName_Cn }} </option>
                                @endforeach
                            </select>
                        </h5>
                        <table class="table table-striped table-bordered table-hover">
                            <thead>
                            <tr class="black br-hblue">
                                <th class="center" width="12%">{{ t('a quarter of a year') }}</th>
                                <th class="center" width="12%">{{ t('Year') }}</th>
                                <th class="center">{{ t('Income') }}</th>
                                <th class="center">{{ t('Expense') }}</th>
                                <th class="center">{{ t('Profit') }}</th>
                                @if(!$isHolder)
                                    <th class="center" width="40px"></th>
                                @endif
                            </tr>
                            </thead>
                            <tbody id="month-table">
							<?php
							$totalIncome = 0;
							$totalExpense = 0;
							$totalProfit = 0;
							?>
                            @for($month = 1 ; $month < 13; $month++)
								<?php
								$exist = 0;
								foreach($monthData as $data) {
									if($data['Month'] == $month){
										$exist = 1;
										$rowData = $data;
										break;
									}
								}
								if($exist == 0) {
									$rowData['Month'] = $month;
									$rowData['Income'] = 0;
									$rowData['Expense'] = 0;
									$rowData['Profit'] = 0;
								}
								$totalIncome += $rowData['Income'];
								$totalExpense += $rowData['Expense'];
								$totalProfit += $rowData['Profit'];
								?>
                                <tr>
                                    @if($month % 3 == 1)
                                        <td rowspan="3">{{ round($month / 3) + 1 }}</td>
                                    @endif
                                    <td class="center">{{$rowData['Month']}}</td>
                                    <td style="text-align: right" data-value="{{$rowData['Income']}}">{{ \App\Http\Controllers\Util::getNumberFtNZ($rowData['Income']) }}</td>
                                    <td style="text-align: right" data-value="{{$rowData['Expense']}}">{{ \App\Http\Controllers\Util::getNumberFtNZ($rowData['Expense']) }}</td>
                                    <td style="text-align: right" data-value="{{$rowData['Profit']}}">{{ \App\Http\Controllers\Util::getNumberFtNZ($rowData['Profit']) }}</td>
                                    @if(!$isHolder)
                                        <td class="action-buttons">
                                            <div class="default">
                                                <a href="javascript:void(0);" class="blue prefit-edit"><i class="icon-edit bigger-130"></i></a>
                                            </div>
                                            <div class="apply" style="display: none">
                                                <a href="javascript:void(0);" class="red prefit-save"><i class="icon-save bigger-130"></i></a>
                                            </div>
                                        </td>
                                    @endif
                                </tr>
                            @endfor
                            </tbody>
                            <tfoot>
                            <tr>
                                <td colspan="2" style="font-weight: bold;text-align:center;">{{ t('Sum') }}</td>
                                <td id="total_income" style="text-align: right;font-weight: bold">{{ \App\Http\Controllers\Util::getNumberFtNZ($totalIncome) }}</td>
                                <td id="total_expense" style="text-align: right;font-weight: bold">{{ \App\Http\Controllers\Util::getNumberFtNZ($totalExpense) }}</td>
                                <td id="total_profit" style="text-align: right;font-weight: bold">{{ \App\Http\Controllers\Util::getNumberFtNZ($totalProfit) }}</td>
                            </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>
                <div class="col-md-4" style="padding-left:40px">
                    <form action="updateYearInputPlan" method="POST">
                        <h5>
                            <b><span>{{ $year }}</span> {{t('Year')}}  <span> {{ $shipName }} </span>{{ t('Plan Input') }}</b>
                        </h5>
                        <div class="space-6"></div>
                        <input type="hidden" name="shipId" value="{{$shipID}}">
                        <input type="hidden" name="year" value="{{$year}}">
                        <input type="hidden" name="submit_type" value="">
                        <input type="hidden" name="_token" value="{{csrf_token()}}">
                        <table class="table table-striped table-bordered table-hover">
                            <tbody>
                            <tr>
                                <td style="text-align: left;padding-left: 20px !important;font-weight: bold;">
                                    {{ t('INCOME') }}
                                </td>
                                <td style="text-align: right;">
                                    <input type="number" name="INCOME" value="{{$yearPlan['INCOME']}}" style="text-align: right;font-weight: bold;"> $
                                </td>
                            </tr>
                            <tr>
                                <td style="text-align: left; padding-left: 20px !important;font-weight: bold;">
                                    {{ t('EXPENSE') }}
                                </td>
                                <td style="text-align: right;">
                                    <input type="number" name="EXPENSE" value="{{$yearPlan['EXPENSE']}}" style="text-align: right;font-weight: bold;"> $
                                </td>
                            </tr>
                            <tr>
                                <td style="text-align: right;font-weight: bold;">
                                    {{ t('BUNKER') }}
                                </td>
                                <td style="text-align: right;">
                                    <input type="number" name="BUNKER" value="{{$yearPlan['BUNKER']}}" style="text-align: right;font-weight: bold;"> $
                                </td>
                            </tr>
                            <tr>
                                <td style="text-align: right;">
                                    {{ t('FO') }}
                                </td>
                                <td style="text-align: right;">
                                    <input type="number" name="FO" value="{{$yearPlan['FO']}}" style="text-align: right;"> $
                                </td>
                            </tr>
                            <tr>
                                <td style="text-align: right;">
                                    {{ t('DO') }}
                                </td>
                                <td style="text-align: right;">
                                    <input type="number" name="DO" value="{{$yearPlan['DO']}}" style="text-align: right;"> $
                                </td>
                            </tr>
                            <tr>
                                <td style="text-align: right;">
                                    {{ t('LO') }}
                                </td>
                                <td style="text-align: right;">
                                    <input type="number" name="LO" value="{{$yearPlan['LO']}}" style="text-align: right;"> $
                                </td>
                            </tr>
                            <tr>
                                <td style="text-align: right;">
                                    {{ t('SS') }}
                                </td>
                                <td style="text-align: right;">
                                    <input type="number" name="SS" value="{{$yearPlan['SS']}}" style="text-align: right;"> $
                                </td>
                            </tr>
                            <tr>
                                <td style="text-align: right;">
                                    {{ t('PDA') }}
                                </td>
                                <td style="text-align: right;">
                                    <input type="number" name="PDA" value="{{$yearPlan['PDA']}}" style="text-align: right;"> $
                                </td>
                            </tr>
                            <tr>
                                <td style="text-align: right;">
                                    {{ t('CTM') }}
                                </td>
                                <td style="text-align: right;">
                                    <input type="number" name="CTM" value="{{$yearPlan['CTM']}}" style="text-align: right;"> $
                                </td>
                            </tr>
                            <tr>
                                <td style="text-align: right;">
                                    {{ t('INSURANCE') }}
                                </td>
                                <td style="text-align: right;">
                                    <input type="number" name="INSURANCE" value="{{$yearPlan['INSURANCE']}}" style="text-align: right;"> $
                                </td>
                            </tr>
                            <tr>
                                <td style="text-align: right;">
                                    {{ t('OAP') }}
                                </td>
                                <td style="text-align: right;">
                                    <input type="number" name="OAP" value="{{$yearPlan['OAP']}}" style="text-align: right;"> $
                                </td>
                            </tr>
                            <tr>
                                <td style="text-align: right;">
                                    {{ t('TELCOM') }}
                                </td>
                                <td style="text-align: right;">
                                    <input type="number" name="TELCOM" value="{{$yearPlan['TELCOM']}}" style="text-align: right;"> $
                                </td>
                            </tr>
                            <tr>
                                <td style="text-align: right;">
                                    {{ t('DUNNAGE') }}
                                </td>
                                <td style="text-align: right;">
                                    <input type="number" name="DUNNAGE" value="{{$yearPlan['DUNNAGE']}}" style="text-align: right;"> $
                                </td>
                            </tr>
                            <tr>
                                <td style="text-align: right;">
                                    {{ t('ISM') }}
                                </td>
                                <td style="text-align: right;">
                                    <input type="number" name="ISM" value="{{$yearPlan['ISM']}}" style="text-align: right;"> $
                                </td>
                            </tr>
                            <tr>
                                <td style="text-align: right;">
                                    {{ t('ORDERS') }}
                                </td>
                                <td style="text-align: right;">
                                    <input type="number" name="OTHERS" value="{{$yearPlan['OTHERS']}}" style="text-align: right;"> $
                                </td>
                            </tr>
                            <tr>
                                <td style="text-align: right;">
                                    {{ t('DOCKING') }}
                                </td>
                                <td style="text-align: right;">
                                    <input type="number" name="DOCKING" value="{{$yearPlan['DOCKING REPAIR']}}" style="text-align: right;"> $
                                </td>
                            </tr>
                            <tr>
                                <td style="text-align: left;padding-left: 20px !important;font-weight: bold;">
                                    {{ t('PROFIT') }}
                                </td>
                                <td style="text-align: right;">
                                    <input type="number" name="PROFIT" value="{{$yearPlan['PROFIT']}}" style="text-align: right;font-weight: bold;"> $
                                </td>
                            </tr>
                            <tr>
                                <td style="text-align: left;padding-left: 20px !important;font-weight: bold;">
                                    {{ t('YEARLY VOY DAY') }}
                                </td>
                                <td style="text-align: right;">
                                    <input type="number" name="VOY_DAY" value="{{$yearPlan['YEARLY VOY DAY']}}" style="text-align: right;font-weight: bold;"> DAY
                                </td>
                            </tr>
                            </tbody>
                        </table>
                        @if(!$isHolder)
                            <button type="submit" class="btn btn-sm btn-primary" id="submit-btn" style="float:right; width :80px;"><i class="icon-save"></i>登记</button>
                            <a class="btn btn-sm btn-success" id="re-calc" style="float:right;margin-right: 15px"><i class="icon-keyboard"></i>计算利润</a>
                        @endif
                    </form>
                </div>
            </div>

        </div>
    </div>

    <script>
        var token = '<?php echo csrf_token() ?>';
        var status = '{!! $status !!}';
        var msg = '{!! $msg !!}';
        $(function () {
            if(status == 'success') {
                var year = $('#select_year').val();
                $.gritter.add({
                    title: '操作成功!',
                    text: msg,
                    class_name: 'gritter-success'
                });
            }

            $('.select_plan').on('change', function(){
                var year = $('#select_year').val();
                var ship = $('#select_ship').val();
                location.href = 'operationPlan?year=' + year + '&shipId=' + ship;
            });

            $('#re-calc').on('click', function () {
                $shipName = "<?php print_r($shipName);?>";
                $year = "<?php print_r($year);?>";
                bootbox.confirm($year + "年[" + $shipName + "] 号的实在利润计算这要进行吗?", function (result) {
                    if(result) {
                        $('[name=submit_type]').val('calc');
                        $('#submit-btn').click();
                    }
                });
            })
            /**/
            $('.plan_modify').on('click', function(){
                // replace content
                var obj = $(this).closest('tr').children();
                var income = obj.eq(1).data('value');
                var expense = obj.eq(2).data('value');
                var profit = obj.eq(3).data('value');
                var remark = obj.eq(4).text();

                obj.eq(1).html("<input type='number' data-old='" +  obj.eq(1).text()  + "' value='"+ income +"'>");
                obj.eq(2).html("<input type='number' data-old='" + obj.eq(2).text()  + "' value='"+ expense +"'>");
                obj.eq(3).html("<input type='number' data-old='" + obj.eq(3).text()  + "' value='"+ profit +"'>");
                obj.eq(4).html("<input type='text' data-old='" + remark  + "' value='"+ remark +"'>");
                //
                obj.eq(5).find('.plan_btns_default').hide();
                obj.eq(5).find('.plan_btns_apply').show();
            });

            // edit cancel
            $('.plan_cancel').on('click', function(){
                // replace content to original value
                var obj = $(this).closest('tr').children();
                obj.eq(1).html( obj.eq(1).find('input').data('old') );
                obj.eq(2).html( obj.eq(2).find('input').data('old') );
                obj.eq(3).html( obj.eq(3).find('input').data('old') );
                obj.eq(4).html( obj.eq(4).find('input').data('old') );
                //
                obj.eq(5).find('.plan_btns_default').show();
                obj.eq(5).find('.plan_btns_apply').hide();
            });

            // update row data
            $('.plan_apply').on('click', function(){
                // replace content to original value

                var obj = $(this).closest('tr').children();

                var shipName = obj.eq(0).html();
                var shipID = obj.eq(0).data('id');
                var year = $('#select_year').val();
                var income = obj.eq(1).find('input').val();
                var expense = obj.eq(2).find('input').val();
                var profit = obj.eq(3).find('input').val();
                var remark = obj.eq(4).find('input').val();

                // save in db
                $.post("updateYearPlan", {'_token': token, 'year': year, 'shipID': shipID,'income': income, 'expense': expense, 'profit': profit, 'remark': remark
                }, function (data) {
                    var result = jQuery.parseJSON(data);
                    if (result.status == 'success') {
                        $.gritter.add({
                            title: '操作成功!',
                            text: '['+ shipName + '] 号' + '的运用计划被修改好了。',
                            class_name: 'gritter-success'
                        });
                        obj.eq(1).html( formatNumber(income) );
                        obj.eq(2).html( formatNumber(expense) );
                        obj.eq(3).html( formatNumber(profit) );
                        obj.eq(1).attr('data-value', income);
                        obj.eq(2).attr('data-value', expense);
                        obj.eq(3).attr('data-value', profit);

                        obj.eq(4).html( remark );
                        //
                        obj.eq(5).find('.plan_btns_default').show();
                        obj.eq(5).find('.plan_btns_apply').hide();

                        var rows = $('#plan-table').children();
                        var len = rows.length - 1;
                        var row = 0;
                        var totalImcome = 0;
                        var totalExpense = 0;
                        var totalProfit = 0;
                        for(; row<len; row++) {
                            var trs = rows.eq(row).children();
                            totalImcome += (trs.eq(1).attr('data-value') * 1);
                            totalExpense += (trs.eq(2).attr('data-value') * 1);
                            totalProfit += (trs.eq(3).attr('data-value') * 1);
                        }
                        $('.income_sum').html(formatNumber(totalImcome));
                        $('.expense_sum').html(formatNumber(totalExpense));
                        $('.profit_sum').html(formatNumber(totalProfit));
                    }
                });

            });
            // insert new row data
            $('.plan_add').on('click', function(){

                var obj = $(this).closest('tr').children();

                var shipID = obj.eq(0).find('select').val();
                var year = $('#select_year').val();
                var income = obj.eq(1).find('input').val();
                var expense = obj.eq(2).find('input').val();
                var profit = obj.eq(3).find('input').val();
                var remark = obj.eq(4).find('textarea').val();

                // save in db
                $.post("updateYearPlan", {'_token': token, 'year': year, 'shipID': shipID,'income': income, 'expense': expense, 'profit': profit, 'remark': remark
                }, function (data) {
                    var result = jQuery.parseJSON(data);
                    if (result.status == 'success') {
                        location.reload();
                    }
                });

            });
            // remove a row
            $('.plan_trash').on('click', function(){

                var obj = $(this).closest('tr').children();
                var shipID = obj.eq(0).data('id');
                var year = $('#select_year').val();

                var tbody = document.getElementById('plan-table');
                var rows = $('#plan-table').children();
                var len = rows.length - 1;
                var row = 0;
                var shipName = '';
                for(; row<len; row++) {
                    var trs = rows.eq(row).children();
                    shipName = trs.eq(0).text();
                    var selShipId = trs.eq(0).data('id');
                    if(shipID == selShipId)
                        break;
                }

                bootbox.confirm("["+ shipName + "]号的 " + year + "年运用计划真要删掉吗?", function(result) {
                    if(result) {
                        // save in db
                        $.post("removeYearPlan", {'_token': token, 'year': year, 'shipID': shipID
                        }, function (data) {
                            var result = jQuery.parseJSON(data);
                            if (result.status == 'success') {
                                tbody.deleteRow(row);
                            }
                        });
                    }
                });
            });

            $('.prefit-edit').on('click', function () {
                var obj = $(this).closest('tr').children();
                var leng = obj.length;
                var start = 2;
                if(leng < 6)
                    start = 1;
                var income = obj.eq(start).data('value');
                var expense = obj.eq(start + 1).data('value');
                var profit = obj.eq(start + 2).data('value');

                obj.eq(start).html("<input type='number' class='form-control' value='" + income +"'>");
                obj.eq(start+1).html("<input type='number' class='form-control' value='" + expense +"'>");
                obj.eq(start+2).html("<input type='number' class='form-control' value='" + profit +"'>");
                //
                obj.eq(start+3).find('.default').hide();
                obj.eq(start+3).find('.apply').show();
            });

            $('.prefit-save').on('click', function () {
                var obj = $(this).closest('tr').children();
                var leng = obj.length;
                var start = 2;
                if(leng < 6)
                    start = 1;
                var year = $('#select_year').val();
                var month = obj.eq(start-1).text();
                var income = obj.eq(start).find('input').val();
                var expense = obj.eq(start+1).find('input').val();
                var profit = obj.eq(start+2).find('input').val();
                var shipId = $('#select_ship').val();

                $.post('updateQuarterMonthPlan',
                    {'_token':token, 'ship':shipId, 'year':year, 'month':month, 'income':income, 'expense':expense, 'profit':profit},
                    function (data) {
                        var result = jQuery.parseJSON(data);
                        if (result.status == 'success') {
                            $.gritter.add({
                                title: '操作成功!',
                                text: year + '年' + month + '月的运用计划被修改好了。',
                                class_name: 'gritter-success'
                            });
                            obj.eq(start).html(formatNumber(income));
                            obj.eq(start + 1).html(formatNumber(expense));
                            obj.eq(start + 2).html(formatNumber(profit));
                            obj.eq(start).attr('data-value', income);
                            obj.eq(start + 1).attr('data-value', expense);
                            obj.eq(start + 2).attr('data-value', profit);

                            //
                            obj.eq(start + 3).find('.default').show();
                            obj.eq(start + 3).find('.apply').hide();

                            var rows = $('#month-table').children();
                            var len = rows.length;
                            var row = 0;
                            var totalImcome = 0;
                            var totalExpense = 0;
                            var totalProfit = 0;
                            for (; row < len; row++) {
                                var trs = rows.eq(row).children();
                                var tdCount = trs.length;
                                start = 2;
                                if (tdCount < 6)
                                    start = 1;
                                totalImcome += (trs.eq(start).attr('data-value') * 1);
                                totalExpense += (trs.eq(start + 1).attr('data-value') * 1);
                                totalProfit += (trs.eq(start + 2).attr('data-value') * 1);
                            }
                            $('#total_income').html(formatNumber(totalImcome));
                            $('#total_expense').html(formatNumber(totalExpense));
                            $('#total_profit').html(formatNumber(totalProfit));

                            obj.eq(start+3).find('.default').show();
                            obj.eq(start+3).find('.apply').hide();
                        }
                    });
                //
            });

            $("input[type=number]").on("change", function(){
                $income = parseFloat($("input[name=INCOME]").val());
                $bunker = parseFloat($("input[name=BUNKER]").val());
                $fo = parseFloat($("input[name=FO]").val());
                $do = parseFloat($("input[name=DO]").val());
                $lo = parseFloat($("input[name=LO]").val());
                $ss = parseFloat($("input[name=SS]").val());
                $pda = parseFloat($("input[name=PDA]").val());
                $ctm = parseFloat($("input[name=CTM]").val());
                $insurance = parseFloat($("input[name=INSURANCE]").val());
                $oap = parseFloat($("input[name=OAP]").val());
                $telcom = parseFloat($("input[name=TELCOM]").val());
                $dunnage = parseFloat($("input[name=DUNNAGE]").val());
                $ism = parseFloat($("input[name=ISM]").val());
                $others = parseFloat($("input[name=OTHERS]").val());
                $docking = parseFloat($("input[name=DOCKING]").val());

                $expense = $bunker + $fo + $do + $lo + $ss + $pda + $ctm + $insurance + $oap + $telcom + $dunnage + $ism + $others + $docking;
                $profit = $income - $expense;
                $("input[name=EXPENSE]").val($expense);
                $("input[name=PROFIT]").val($profit);
            });

        });
    </script>

@stop
