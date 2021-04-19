@extends('layout.header')

@section('content')
    <div class="main-content">
        <style>
            .table thead>tr>th {
                padding: 6px 3px;
            }
        </style>
        <div class="page-content">
            <div class="page-header">
                <div class="col-md-3">
                    <h4>
                        <b>{{transOrgManage("title.MemberInfo")}}</b>
                    </h4>
                </div>
            </div>
            <div class="col-md-12">
                <div class="row">
                    <div class="col-sm-12">
                        <div class="col-md-2">
                            <label class="font-bold" style="float:left;padding-top:7px">{{transOrgManage("captions.department")}}:</label>
                            <div class="col-md-9" style="padding-left:10px">
                                <select class="custom-select d-inline-block select_unit" style="max-width:120px;">
                                    <option value="">{{transOrgManage("captions.total")}}</option>
                                    @foreach($unitList as $unit)
                                        <option value="{{$unit['id']}}" @if(isset($unitId) && ($unitId == $unit['id'])) selected @endif>{{$unit['title']}}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-md-2">
                            <label class="font-bold" style="float:left;padding-top:7px">{{transOrgManage("captions.officePosition")}}:</label>
                            <div class="col-md-9" style="padding-left:10px">
                                <select class="custom-select d-inline-block select_pos">
                                    <option value="">{{transOrgManage("captions.total")}}</option>
                                    @foreach($posList as $pos)
                                        <option value="{{$pos['id']}}" @if(isset($posId) && ($posId == $pos['id'])) selected @endif>{{$pos['title']}}</option>
                                    @endforeach
                                    <option value="{{ IS_SHAREHOLDER }}">船东</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-2 input-group">
                            <label class="font-bold">{{transOrgManage("captions.name")}}:</label>
                            <input type="text" class="realname" style="width:65%;margin-left: 10px" value="@if(isset($realname)){{$realname}}@endif">
                        </div>
                        <div class="col-md-2">
                            <label class="font-bold" style="float: left;padding-top:7px">{{transOrgManage("captions.status")}}:</label>
                            <div class="col-md-8" style="float:left;padding-left: 10px">
                                <select class="custom-select d-inline-block select_status">
                                    <option value="">{{transOrgManage("captions.total")}}</option>
                                    @foreach(g_enum('EmployeeStatusData') as $key => $item)
                                        <option value="{{ $key }}" @if(isset($status) && ($status == $key)) selected @endif>{{ $item[0] }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-md-3 f-right">
                        <button class="btn btn-primary btn-sm search-btn" style="float:left; width: 80px"><i class="icon-search"></i>{{transOrgManage("captions.search")}}</button>
                            <a href="{{ url('org/memberadd') }}" class="btn btn-success btn-sm">
                                <i class="icon-plus-sign-alt"></i>{{transOrgManage("captions.add")}}
                            </a>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="space-2"></div>
                    <div class="table-responsive" id="user_list_table">
                        <table class="table table-striped table-bordered table-hover" id="user-table">
                            <thead>
                            <tr class="black br-hblue">
                                <th class="text-center style-normal-header" rowspan="2">No</th>
                                <th class="text-center style-normal-header">{{transOrgManage("captions.department")}}</th>
                                <th class="text-center style-normal-header" rowspan="2">{{transOrgManage("captions.name")}}</th>
                                <th class="text-center style-normal-header" rowspan="2">{{transOrgManage("captions.loginID")}}</th>
                                <th class="text-center style-normal-header" rowspan="2">{{transOrgManage("captions.phoneNumber")}}</th>
                                <th class="text-center style-normal-header" rowspan="2">{{transOrgManage("captions.level")}}</th>
                                <th class="text-center style-normal-header" rowspan="2">{{transOrgManage("captions.status")}}</th>
                                <th class="text-center style-normal-header" rowspan="2">{{transOrgManage("captions.enterDate")}}</th>
                                <th class="text-center style-normal-header" rowspan="2">{{transOrgManage("captions.exitDate")}}</th>
                                <th rowspan="2" width="50px"></th>
                            </tr>
                            <tr class="black br-hblue">
                                <th class="text-center style-normal-header">{{transOrgManage("captions.officePosition")}}</th>
                            </tr>
                            </thead>
                            <tbody>
                            @if (isset($list) && count($list) > 0)
								<?php $index = ($list->currentPage() - 1) * 15 + 1; ?>
                                @foreach ($list as $userInfo)

                                    <tr>
                                        <td rowspan="2" class="center">{{$index++}}</td>
                                        <td class="center">{{is_null($userInfo['unitTitle'])?'&nbsp;':$userInfo['unitTitle']}}</td>
                                        <td rowspan="2" class="center">{{$userInfo['realname']}}</td>
                                        <td rowspan="2" class="center">{{$userInfo['account']}}</td>
                                        <td class="center" rowspan="2">{{$userInfo['phone']}}</td>
                                        <td class="center" rowspan="2"><span class="badge badge-{{ g_enum('UserLabelInfo')[$userInfo['isAdmin']][1] }}">{{ g_enum('UserLabelInfo')[$userInfo['isAdmin']][0] }}</span></td>
                                        <td class="center" rowspan="2"><span class="badge badge-{{ g_enum('EmployeeStatusData')[$userInfo['status']][1] }}">{{ g_enum('EmployeeStatusData')[$userInfo['status']][0] }}</span></td>
                                        <td class="center" rowspan="2">{{$userInfo['entryDate']}}</td>
                                        <td rowspan="2" class="center">{{$userInfo['releaseDate']}}</td>
                                        <td class="action-buttons center" rowspan="2">
                                            <a class="blue" href="{{ 'memberadd' }}?uid={{$userInfo->id}}">
                                                <i class="icon-edit bigger-130"></i>
                                            </a>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="center">{{is_null($userInfo['posTitle']) ? '&nbsp;':$userInfo['posTitle']}}</td>
                                    </tr>
                                @endforeach
                            @else
                                <tr>
                                    <td colspan="10">{{ trans('common.message.no_data') }}</td>
                                </tr>
                            @endif
                            </tbody>
                        </table>
                        {!! $list->render() !!}
                    </div>
                </div>
            </div>
        </div>
    </div><!-- /.main-content -->

    <script>

        function filterByUnitName() {
            var keyword = $("#search_unit").val();
            $("#user-table_filter").find('input[type="text"]').val(keyword).trigger("keyup.DT");
        }

        $(function () {
            $('.search-btn').on('click', function () {
                var unit = $('.select_unit').val();
                var pos = $('.select_pos').val();
                var realname = $('.realname').val();
                var status = $('.select_status').val();

                var param = '';
                if(unit.length > 0)
                    param = '?unit=' + unit;
                if(pos.length > 0)
                    param = (param == '' ? '?' : param + '&') + 'pos=' + pos;

                if(realname.length > 0)
                    param = (param == '' ? '?' : param + '&') + 'realname=' + realname;

                if(status.length > 0)
                    param = (param == '' ? '?' : param + '&') + 'status=' + status;

                location.href = 'userInfoListView' + param;
            });

            $('.init-btn').on('click', function() {

                location.href = 'userInfoListView';
            });

        })

    </script>
@stop
