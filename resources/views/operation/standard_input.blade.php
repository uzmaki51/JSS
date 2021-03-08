@extends('layout.sidebar')

@section('content')

    <div class="main-content">
        <style>
            .pagination {
                margin-bottom: 12px;
                margin-top: 1px;
            }

            ul {
                margin: 0 0 0px 10px;
            }
        </style>
        <div class="page-content">
            <div class="page-header">
                <div class="col-md-6">
                    <h4><b>항차타산</b>
                        <small>
                            <i class="icon-double-angle-right"></i>
                        </small>
                    </h4>
                </div>
                <div class="col-sm-6" style="text-align:right">
                    <a href="javascript:history.back()">이전페지</a>
                </div>
            </div>
            <div class="col-md-12">
                <form method="POST" action="calculateVoyageData" id="calculuate_form">
                    <input class="hidden" name="_token" value="{{csrf_token()}}">
                    <div class="row">
                        <div class="col-md-7">
                            <div class="space-10"></div>
                            <div class="col-md-5">
                                <label style="float: left;padding-top:7px">{{transShipOperation("standard.ShipName")}}:</label>
                                <select class="form-control ship_select" name="shipId" style="width:60%;float: left;margin-left:10px">
                                    <option value=""></option>
                                    @foreach($shipList as $ship)
                                        <option value="{{$ship['RegNo']}}">{{$ship['shipName_En']}} | {{$ship['shipName_Cn']}}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-5">
                                <label style="float: left;padding-top:7px">{{transShipOperation("standard.Voy")}}:</label>
                                <select class="form-control" name="voyId" style="width:70%;float:left;margin-left:10px" id="voy_select">
                                    <option value=""></option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="space-12"></div>
                        <div class="col-md-6">
                            <div class="panel panel-default">
                                <div class="panel-heading" style="padding-top:1px;padding-bottom: 1px">
                                    <div class="panel-title">
                                        <h5>1. {{transShipOperation("standard.Voyage Qantitative")}}
                                            <a class="btn btn-success btn-sm btn-distance" style="margin-top:-4px;float: right">{{transShipOperation("standard.Distance Record")}}</a>
                                        </h5>

                                    </div>
                                </div>
                                <div class="panel-body">
                                    <table class="arc-std-table table table-striped table-bordered">
                                        <tr>
                                            <td style="width:27%">{{transShipOperation("standard.Speed")}}[Kn]<label style="color: red;margin-top:-6px">*</label></td>
                                            <td><input type="number" style="text-align: center" class="form-control" name="Speed"></td>
                                            <td style="width:30%;text-align: center">{{transShipOperation("standard.Distance")}}[Mile]<label style="color: red;margin-top:-6px">*</label></td>
                                            <td><input type="number" style="text-align: center" class="form-control" name="Distance"></td>
                                        </tr>
                                        <tr>
                                            <td>{{transShipOperation("standard.L/D Day")}}<label style="color: red;margin-top:-6px">*</label></td>
                                            <td><input type="number" style="text-align: center" class="form-control" name="LD_Day"></td>
                                            <td class="center">{{transShipOperation("standard.Idle Day")}}<label style="color: red;margin-top:-6px">*</label></td>
                                            <td><input type="number" style="text-align: center" class="form-control" name="Idle_Day"></td>
                                        </tr>
                                    </table>
                                </div>
                            </div>
                            <div class="panel panel-default">
                                <div class="panel-heading" style="padding-top:1px;padding-bottom: 1px">
                                    <div class="panel-title">
                                        <h5>2. {{transShipOperation("standard.Voyage bunker practical consumption")}}
                                            <a class="btn btn-success btn-sm btn-bunker" style="margin-top:-4px;float: right">{{transShipOperation("standard.Bunker consumption")}}</a>
                                        </h5>
                                    </div>
                                </div>
                                <div class="panel-body">
                                    <table class="table table-striped table-bordered arc-std-table">
                                        <tr>
                                            <td class="center">{{transShipOperation("standard.FO")}}[MT]</td>
                                            <td class="center">{{transShipOperation("standard.DO")}}[MT]</td>
                                            <td class="center">{{transShipOperation("standard.LO")}}[Kg]</td>
                                        </tr>
                                        <tr>
                                            <td><input type="number" style="text-align: center" class="form-control" name="Prac_Fo"></td>
                                            <td><input type="number" style="text-align: center" class="form-control" name="Prac_Do"></td>
                                            <td><input type="number" style="text-align: center" class="form-control" name="Prac_Lo"></td>
                                        </tr>
                                    </table>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="panel panel-default">
                                <div class="panel-heading" style="padding-top:1px;padding-bottom: 1px">
                                    <div class="panel-title">
                                        <h5>3. {{transShipOperation("standard.Daily bunker consumption and price")}}
                                            <a class="btn btn-success btn-sm btn-consum" style="margin-top:-4px;float: right">{{transShipOperation("standard.Bunker consum level")}}</a>
                                        </h5>
                                    </div>
                                </div>
                                <div class="panel-body">
                                    <table class="arc-std-table table table-striped table-bordered">
                                        <tr>
                                            <td style="width:15%"></td>
                                            <td class="center">{{transShipOperation("standard.Sail")}}[MT]<label style="color: red;margin-top:-6px">*</label></td>
                                            <td class="center">{{transShipOperation("standard.L/D")}}[MT]<label style="color: red;margin-top:-6px">*</label></td>
                                            <td class="center">{{transShipOperation("standard.Idle")}}[MT]<label style="color: red;margin-top:-6px">*</label></td>
                                            <td class="center">{{transShipOperation("standard.Price")}}[$/MT]<label style="color: red;margin-top:-6px">*</label></td>
                                        </tr>
                                        <tr>
                                            <td class="center">{{transShipOperation("standard.FO")}}<label style="color: red;margin-top:-6px">*</label></td>
                                            <td><input type="number" style="text-align: center" class="form-control" name="DailyPrac_Fo_Sail"></td>
                                            <td><input type="number" style="text-align: center" class="form-control" name="DailyPrac_Fo_LD"></td>
                                            <td><input type="number" style="text-align: center" class="form-control" name="DailyPrac_Fo_Idle"></td>
                                            <td><input type="number" style="text-align: center" class="form-control" name="Fo_Unit"></td>
                                        </tr>
                                        <tr>
                                            <td class="center">{{transShipOperation("standard.DO")}}<label style="color: red;margin-top:-6px">*</label></td>
                                            <td><input type="number" style="text-align: center" class="form-control" name="DailyPrac_Do_Sail"></td>
                                            <td><input type="number" style="text-align: center" class="form-control" name="DailyPrac_Do_LD"></td>
                                            <td><input type="number" style="text-align: center" class="form-control" name="DailyPrac_Do_Idle"></td>
                                            <td><input type="number" style="text-align: center" class="form-control" name="Do_Unit"></td>
                                        </tr>
                                        <tr>
                                            <td class="center">{{transShipOperation("standard.LO")}}<label style="color: red;margin-top:-6px">*</label></td>
                                            <td><input type="number" style="text-align: center" class="form-control" name="DailyPrac_Lo_Sail"></td>
                                            <td><input type="number" style="text-align: center" class="form-control" name="DailyPrac_Lo_LD"></td>
                                            <td><input type="number" style="text-align: center" class="form-control" name="DailyPrac_Lo_Idle"></td>
                                            <td><input type="number" style="text-align: center" class="form-control" name="Lo_Unit"></td>
                                        </tr>
                                    </table>
                                </div>
                            </div>
                            <div class="panel panel-default">
                                <div class="panel-body">
                                    <table class="table table-striped table-bordered arc-std-table">
                                        <tr>
                                            <td style="text-align: center;width:20%">{{transShipOperation("standard.PD")}} :<label style="color: red;margin-top:-6px">*</label></td>
                                            <td><input type="number" style="text-align: center" class="form-control" name="PDA_Prog"></td>
                                            <td style="text-align: center;width:20%">{{transShipOperation("standard.Add Income")}} :</td>
                                            <td><input type="number" style="text-align: center" class="form-control" name="Pro_AddIn"></td>
                                        </tr>
                                    </table>
                                </div>
                            </div>

                        </div>
                    </div>
                    <div class="row">
                        <div class="space-10"></div>
                        <div class="col-md-1 col-md-offset-5">
                            <button type="submit" class="btn btn-sm btn-primary no-radius" style="float:right; width :80px;">&nbsp;항차타산&nbsp;</button>
                        </div>
                    </div>
                </form>
            </div>
            <a href="#modal-dialog" role="button" class="hidden" data-toggle="modal" id="dialog_show"></a>
            <div id="modal-dialog" class="modal fade" tabindex="-1">
            </div>
        </div>
    </div>


<script>

    var token = '{!! csrf_token() !!}';
    var page = 0;

    $(function () {
        $('#calculuate_form').validate({
            rules: {
               shipId: "required",
               voyId: "required",
               Speed: "required",
               Distance: "required",
               LD_Day: "required",
               Idle_Day: "required",
               DailyPrac_Fo_Sail: "required",
               DailyPrac_Do_Sail: "required",
               DailyPrac_Lo_Sail: "required",
               DailyPrac_Fo_LD: "required",
               DailyPrac_Do_LD: "required",
               DailyPrac_Lo_LD: "required",
               DailyPrac_Fo_Idle: "required",
               DailyPrac_Do_Idle: "required",
               Fo_Unit: "required",
               Do_Unit: "required",
               Lo_Unit: "required",
               PDA_Prog: "required",
            },
            messages: {
               shipId: "must select ShipName",
               voyId: "must select Voy",
               Speed: "must input Speed",
               Distance: "must input Distance",
               LD_Day: "must input L/D Day ",
               Idle_Day: "must input Idle Day",
               DailyPrac_Fo_Sail: "must input Daily Fo Sail",
               DailyPrac_Do_Sail: "must input Daily Do Sail",
               DailyPrac_Lo_Sail: "must input Daily Lo Sail",
               DailyPrac_Fo_LD: "must input Daily Fo L/D",
               DailyPrac_Do_LD: "must input Daily Do L/D",
               DailyPrac_Lo_LD: "must input Daily Lo L/D",
               DailyPrac_Fo_Idle: "must input Daily Fo Idle",
               DailyPrac_Do_Idle: "must input Daily Do Idle",
               DailyPrac_Lo_Idle: "must input Daily Lo Idle",
               Fo_Unit: "must input Fo Unit",
               Do_Unit: "must input Do Unit",
               Lo_Unit: "must input Lo Unit",
               PDA_Prog: "must input PD",
            }
        });

        $('.ship_select').on('change', function () {
            var shipId = $('.ship_select').val();
            $.post('getVoyListAndCaculInfo', {'_token':token, 'shipId':shipId}, function(data) {
                var htmlStr = '';
                if(data) {
                    var result = data;
                    var length = result.voyList.length;
                    $('[name=Speed]').val(result.speed);

                    for(var i=0; i<length; i++) {
                        var voy = result.voyList[i];
                        htmlStr += '<option value="' + voy.id + '">' + voy.Voy_No + ' | ' + voy.CP_No + '</option>';
                    }
                    $("#voy_select").html(htmlStr);
                    $('[name=Distance]').val(result.calcInfo.PracDistance);
                    $('[name=Speed]').val(result.calcInfo.Speed);
                    $('[name=LD_Day]').val(result.calcInfo.LD_Day);
                    $('[name=Idle_Day]').val(result.calcInfo.Idle_Day);
                    $('[name=Prac_Fo]').val(result.calcInfo.Prac_Fo);
                    $('[name=Prac_Do]').val(result.calcInfo.Prac_Do);
                    $('[name=Prac_Lo]').val(result.calcInfo.Prac_Lo);
                    $('[name=DailyPrac_Fo_Sail]').val(result.calcInfo.DailyPrac_Fo_Sail);
                    $('[name=DailyPrac_Fo_LD]').val(result.calcInfo.DailyPrac_Fo_LD);
                    $('[name=DailyPrac_Fo_Idle]').val(result.calcInfo.DailyPrac_Fo_Idle);
                    $('[name=Fo_Unit]').val(result.calcInfo.Fo_Unit);
                    $('[name=DailyPrac_Do_Sail]').val(result.calcInfo.DailyPrac_Do_Sail);
                    $('[name=DailyPrac_Do_LD]').val(result.calcInfo.DailyPrac_Do_LD);
                    $('[name=DailyPrac_Do_Idle]').val(result.calcInfo.DailyPrac_Do_Idle);
                    $('[name=Do_Unit]').val(result.calcInfo.Do_Unit);
                    $('[name=DailyPrac_Lo_Sail]').val(result.calcInfo.DailyPrac_Lo_LD);
                    $('[name=DailyPrac_Lo_LD]').val(result.calcInfo.DailyPrac_Lo_Idle);
                    $('[name=DailyPrac_Lo_Idle]').val(result.calcInfo.DailyPrac_Lo_Idle);
                    $('[name=Lo_Unit]').val(result.calcInfo.Lo_Unit);
                }
            });
        });

        $('#voy_select').on('change', function () {
            var shipId = $('.ship_select').val();
            var voyId = $(this).val();
            $.post('getVoyListAndCaculInfo', {'_token':token, 'shipId':shipId, 'voyId':voyId}, function(data) {
//                var htmlStr = '';
                if(data) {
                    var result = data;

                    $('[name=Distance]').val(result.calcInfo.PracDistance);
                    $('[name=Speed]').val(result.calcInfo.Speed);
                    $('[name=LD_Day]').val(result.calcInfo.LD_Day);
                    $('[name=Idle_Day]').val(result.calcInfo.Idle_Day);
                    $('[name=Prac_Fo]').val(result.calcInfo.Prac_Fo);
                    $('[name=Prac_Do]').val(result.calcInfo.Prac_Do);
                    $('[name=Prac_Lo]').val(result.calcInfo.Prac_Lo);
                    $('[name=DailyPrac_Fo_Sail]').val(result.calcInfo.DailyPrac_Fo_Sail);
                    $('[name=DailyPrac_Fo_LD]').val(result.calcInfo.DailyPrac_Fo_LD);
                    $('[name=DailyPrac_Fo_Idle]').val(result.calcInfo.DailyPrac_Fo_Idle);
                    $('[name=Fo_Unit]').val(result.calcInfo.Fo_Unit);
                    $('[name=DailyPrac_Do_Sail]').val(result.calcInfo.DailyPrac_Do_Sail);
                    $('[name=DailyPrac_Do_LD]').val(result.calcInfo.DailyPrac_Do_LD);
                    $('[name=DailyPrac_Do_Idle]').val(result.calcInfo.DailyPrac_Do_Idle);
                    $('[name=Do_Unit]').val(result.calcInfo.Do_Unit);
                    $('[name=DailyPrac_Lo_Sail]').val(result.calcInfo.DailyPrac_Lo_LD);
                    $('[name=DailyPrac_Lo_LD]').val(result.calcInfo.DailyPrac_Lo_Idle);
                    $('[name=DailyPrac_Lo_Idle]').val(result.calcInfo.DailyPrac_Lo_Idle);
                    $('[name=Lo_Unit]').val(result.calcInfo.Lo_Unit);
                }
//                $("#voy_select").html(htmlStr);
            });
        });
        $('.btn-distance').on('click', function () {
            $.post('showPortDistance', {'_token':token}, function (data) {
                $('#modal-dialog').html(data);
                $('#dialog_show').click();
            })
        });

        $('.btn-bunker').on('click', function () {
            var shipId = $('.ship_select').val();
            var voyId = $('#voy_select').val();

            if(shipId.length < 1 || voyId.length < 1) {
                $.gritter.add({
                    title: '오유',
                    text: '배이름과 항차번호를 먼저 선택하여야 합니다.',
                    class_name: 'gritter-error'
                });
                return;
            }

            $.post('voyLogByShipId', {'_token':token, 'shipId':shipId, 'voyId':voyId}, function (data) {
                $('#modal-dialog').html(data);
                $('#dialog_show').click();
            })
        });

        $('.btn-consum').on('click', function () {
            var shipId = $('.ship_select').val();

            if(shipId.length < 1) {
                $.gritter.add({
                    title: '오유',
                    text: '배이름을 먼저 선택하여야 합니다.',
                    class_name: 'gritter-error'
                });
                return;
            }

            $.post('shipFuelCondition', {'_token':token, 'shipId':shipId}, function (data) {
                $('#modal-dialog').html(data);
                $('#dialog_show').click();
            })
        })
    });


    function bindBtnClickEvent() {
        $('#distance-table td input:checkbox').on('click' , function(){
            var that = this;
            var distance = '';
            if(that.checked == true) {
                var tr = $(this).closest('tr').children();
                distance = tr.eq(5).text();
            }

            $('[name=Distance]').val(distance);

            $(this).closest('table').find('tr > td:first-child input:checkbox')
                    .each(function(){
                        if(this != that)
                            this.checked = false;
                    });

        });

        $('.bunker-table td input:checkbox').on('click' , function(){
            var that = this;
            if(that.checked == true) {
                var tr = $(this).closest('tr').children();
                var fo = tr.eq(5).text();
                var ddo = tr.eq(6).text();
                var lo = tr.eq(7).text();
                $('[name=Prac_Fo]').val(fo);
                $('[name=Prac_Do]').val(ddo);
                $('[name=Prac_Lo]').val(lo);
            } else {
                $('[name=Prac_Fo]').val('');
                $('[name=Prac_Do]').val('');
                $('[name=Prac_Lo]').val('');
            }

            $(this).closest('table').find('tr > td:first-child input:checkbox')
                    .each(function(){
                        if(this != that)
                            this.checked = false;
                    });

        });

        $('.consum-table td input:checkbox').on('click' , function(){
            var that = this;
            var condi = this.className;

            $(this).closest('table').find('tr > td:first-child input:checkbox')
                    .each(function(){
                        if(this != that)
                            this.checked = false;
                    });

            if(that.checked == true) {
                for(var i=0;i<3;i++) {
                    var className = condi;
                    if(i == 0){
                        className += '_fo';
                        $(this).closest('table').find('.'+className)
                                .each(function(){
                                    var tr = $(this).children();
                                    $('[name=DailyPrac_Fo_Sail]').val(tr.eq(3).text());
                                    $('[name=DailyPrac_Fo_LD]').val(tr.eq(4).text());
                                    $('[name=DailyPrac_Fo_Idle]').val(tr.eq(5).text());
                                });
                    } else if(i == 1) {
                        className += '_do';
                        $(this).closest('table').find('.'+className)
                                .each(function(){
                                    var tr = $(this).children();
                                    $('[name=DailyPrac_Do_Sail]').val(tr.eq(1).text());
                                    $('[name=DailyPrac_Do_LD]').val(tr.eq(2).text());
                                    $('[name=DailyPrac_Do_Idle]').val(tr.eq(3).text());
                                });
                    } else {
                        className += '_lo';
                        $(this).closest('table').find('.'+className)
                                .each(function(){
                                    var tr = $(this).children();
                                    $('[name=DailyPrac_Lo_Sail]').val(tr.eq(1).text());
                                    $('[name=DailyPrac_Lo_LD]').val(tr.eq(2).text());
                                    $('[name=DailyPrac_Lo_Idle]').val(tr.eq(3).text());
                                });
                    }
                }
            } else {
                $('[name=DailyPrac_Fo_Sail]').val('');
                $('[name=DailyPrac_Fo_LD]').val('');
                $('[name=DailyPrac_Fo_Idle]').val('');
                $('[name=DailyPrac_Lo_Sail]').val('');
                $('[name=DailyPrac_Lo_LD]').val('');
                $('[name=DailyPrac_Lo_Idle]').val('');
                $('[name=DailyPrac_Do_Sail]').val('');
                $('[name=DailyPrac_Do_LD]').val('');
                $('[name=DailyPrac_Do_Idle]').val('');
            }
        });
    }
</script>
@stop