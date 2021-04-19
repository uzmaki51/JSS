<?php
/**
 * Created by PhpStorm.
 * User: Cmb
 * Date: 2017/11/18
 * Time: 0:29
 */
?>

@extends('layout.header')
@section('content')
    <div class="main-content">
        <div class="page-content">
            <div class="page-header">
                <div class="col-md-6">
                    <h4><b>{{transBusinessManage("title.EnterpriseReport")}}</b>
                        <small>
                            <i class="icon-double-angle-right"></i>
                            {{transBusinessManage("title.MemberReport")}}
                        </small>
                    </h4>
                </div>
            </div>
            <div class="col-md-12">
                <div class="row">
                    <div class="tabbable">
                        <ul class="nav nav-tabs" id="myTab">
                            {{--<li class="active">--}}
                                {{--<a data-toggle="tab" href="#week">--}}
                                    {{--주보열람--}}
                                {{--</a>--}}
                            {{--</li>--}}

                            <li class="active">
                                <a data-toggle="tab" href="#month">
                                {{transBusinessManage("captions.monthreport_read")}}
                                </a>
                            </li>
                        </ul>
                    </div>
                    <div class="tab-content">
                        {{--<div id="week" class="tab-pane active">--}}
                            {{--@include('business.plan.per_member_week', with(['cur_date'=>$cur_date, 'weekList'=>$weekList,'list'=>$weekReport]))--}}
                        {{--</div>--}}

                        <div id="month" class="tab-pane active">
                            @include('business.plan.per_member_month', with(['cur_date'=>$cur_date, 'list'=>$monthReport]))
                        </div>
                    </div>
                </div><!-- /span -->

                <div class="vspace-xs-12"></div>
            </div>
        </div>
    </div>

    <script>
        var token = '{!! csrf_token() !!}';
        function ShowLists( type) {

            switch (type){
                case 'week':
                    var year = $("#week_year").val();
                    var month = $("#week_month").val();
                    var week = $("#cur_week").val();
                    $.post('memberWeekReport', {'_token':token, 'year':year, 'month':month, 'week':week}, function (data) {
                        $('#week').html(data);
                    });
                    break;
                case 'month':
                    var year = $("#cur_year").val();
                    var month = $("#cur_month").val();

                    $.post('memberMonthReport', {'_token':token, 'year': year, 'month':month}, function (data) {
                        $('#month').html(data);
                    });
                    break;
            }
        }
        function ShowListsExcel( type) {

            switch (type){
                case 'week':
                    var year = $("#week_year").val();
                    var month = $("#week_month").val();
                    var week = $("#cur_week").val();
                    location.href = 'reportPerMemberWeekExcel?year='+year+'&month='+month+'&week='+week;
                    break;
                case 'month':
                    var year = $("#cur_year").val();
                    var month = $("#cur_month").val();
                    location.href = 'reportPerMemberMonthExcel?year='+year+'&month='+month;
                    break;
            }
        }


    </script>
@endsection
