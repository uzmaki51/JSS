@extends('layout.header')

@section('content')
    <div class="main-content">
        <style>
            .table thead>tr>th {
                padding: 6px 3px;
            }

            .member-item-odd {
                background-color: #efefef;
            }

            .member-item-even:hover {
                background-color: #ffe3e082;
            }

            .member-item-odd:hover {
                background-color: #ffe3e082;
            }
        </style>
        <div class="page-content">
            <div class="page-header">
                <div class="col-sm-3">
                    <h4><b>{{transOrgManage("title.MemberInfo")}}</b></h4>
                </div>
            </div>
            <div class="col-md-12" style="margin-top:4px;">
                <div class="row">
                    <div class="col-md-12">
                        <div class="col-md-7">
                            <strong class="f-right" style="font-size: 20px; padding-top: 6px;">吉速船舶有限公司</strong>
                        </div>
                        <div class="col-md-5" style="padding:unset!important">
                            <div class="btn-group f-right">
                                <a href="{{ url('org/memberadd') }}" class="btn btn-sm btn-primary btn-add" style="width: 80px">
                                    <i class="icon-plus"></i>{{ trans('common.label.add') }}
                                </a>
                                <a onclick="javascript:fnExcelReport();" class="btn btn-warning btn-sm excel-btn">
                                    <i class="icon-table"></i>{{ trans('common.label.excel') }}
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-12" style="margin-top:4px;">
                    <div id="item-manage-dialog" class="hide"></div>
                    <input type="hidden" name="_token" value="{{csrf_token()}}">
                    <div class="row">
                        <div class="head-fix-div common-list" id="crew-table" style="">
                            <table id="table-shipmember-list" style="table-layout:fixed;">
                                <thead class="">
                                    <th class="text-center style-normal-header" style="width: 3%;height:35px;"><span>No</span></th>
                                    <th class="text-center style-normal-header" style="width: 10%;"><span>姓名</span></th>
                                    <th class="text-center style-normal-header" style="width: 10%;"><span>ID</span></th>
                                    <th class="text-center style-normal-header" style="width: 10%;"><span>职位</span></th>
                                    <th class="text-center style-normal-header" style="width: 15%;"><span>手机号码</span></th>
                                    <th class="text-center style-normal-header" style="width: 9%;"><span>到职日期</span></th>
                                    <th class="text-center style-normal-header" style="width: 9%;"><span>退职日期</span></th>
                                    <th class="text-center style-normal-header" style="width: 30%;"><span>备注</span></th>
                                    <th class="text-center" style=""></th>
                                </thead>
                                <tbody class="" id="list-body">
                                @if (isset($list) && count($list) > 0)
                                <?php $index = 1;?>
                                @foreach ($list as $userInfo)
                                    <tr @if($index%2==0) class="member-item-odd" @else class="member-item-even" @endif>
                                        <td class="center" style="height:35px;">{{$index++}}</td>
                                        <td class="center">{{$userInfo['realname']}}</td>
                                        <td class="center">{{$userInfo['account']}}</td>
                                        <td class="center">{{is_null($userInfo['posTitle']) ? '&nbsp;':$userInfo['posTitle']}}</td>
                                        <td class="center">{{$userInfo['phone']}}</td>
                                        <td class="center">{{$userInfo['entryDate']}}</td>
                                        <td class="center">{{$userInfo['releaseDate']}}</td>
                                        <td class="center">{{$userInfo['remark']}}</td>
                                        <td class="action-buttons center">
                                            <a class="blue" href="{{ 'memberadd' }}?uid={{$userInfo->id}}">
                                                <i class="icon-edit bigger-130"></i>
                                            </a>
                                        </td>
                                    </tr>
                                @endforeach
                                @else
                                <tr>
                                    <td colspan="9">{{ trans('common.message.no_data') }}</td>
                                </tr>
                                @endif
                                </tbody>
                            </table>
                        </div>
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
