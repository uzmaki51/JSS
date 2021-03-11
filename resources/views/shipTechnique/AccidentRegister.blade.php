@extends('layout.sidebar')
<?php
$isHolder = Session::get('IS_HOLDER');
$ships = Session::get('shipList');
?>
@section('content')
    <div class="main-content">
        <div class="page-content">
            <div class="page-header">
                <div class="col-md-3">
                    <h4>
                        <b>船舶事故</b>
                        <small>
                            <i class="icon-double-angle-right"></i>
                            报告登记
                        </small>
                    </h4>
                </div>
            </div>

            <div class="col-md-12">
                <div class="row">
                    <div class="col-sm-3">
                        <label style="float:left;padding-top:5px">船舶名称 :</label>
                        <div class="col-md-8" style="padding-left:5px">
                            <select class="form-control" id="search_ship_id">
                                <option value=""> </option>
                                @foreach($shipList as $ship)
                                    @if(!$isHolder)
                                        <option value="{{$ship['RegNo']}}"
                                                @if(isset($shipId) && ($shipId == $ship['RegNo'])) selected @endif>{{ $ship['shipName_Cn'] .' | ' .$ship['shipName_En']}}
                                        </option>
                                    @elseif(in_array($ship->shipID, $ships))
                                        <option value="{{$ship['RegNo']}}"
                                                @if(isset($shipId) && ($shipId == $ship['RegNo'])) selected @endif>{{ $ship['shipName_Cn'] .' | ' .$ship['shipName_En']}}
                                        </option>
                                    @endif
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="col-sm-2">
                        <label style="float:left;padding-top:5px">航次号码:</label>
                        <div class="col-md-6" style="padding-left:5px" id="Voy">
                            <select class="form-control" id="search_voy_number" >
                                <option value=""></option>
                                @foreach($cps as $cp)
                                    <option value="{{$cp['CP_No']}}" @if(isset($voy)&&($voy==$cp['CP_No'])) selected @endif>
                                        {{$cp['Voy_No']}} | {{$cp['CP_No']}}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="col-sm-5" style="text-align: right;float:right;">
                        <button class="btn btn-sm btn-primary no-radius search-btn" style="width: 80px">
                            <i class="icon-search"></i>
                            搜索
                        </button>
                        @if(!$isHolder)
                            <a class="btn btn-sm btn-primary no-radius" href="shipAccidentDetail" style="width: 80px">
                                <i class="icon-plus-sign-alt"></i>
                                追加
                            </a>
                        @endif
                    </div>
                </div>
                <div class="row">
                    <div class="space-4"></div>
                    <table class="table table-striped table-bordered table-hover">
                        <thead>
                        <tr class="black br-hblue">
                            <th class="center" >船舶名称</th>
                            <th class="center">航次号码</th>
                            <th class="center">日期</th>
                            <th class="center">港口名称</th>
                            <th class="center">地点</th>
                            <th class="center">内容</th>
                            <th class="center">种类</th>
                            <th class="center">附件</th>
                            @if(!$isHolder)
                                <th class="center" width="70px"></th>
                            @endif
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($AccidentInfos as $AccidentInfo)
                            <tr class="center">
                                <td class="center" data-id="{{$AccidentInfo['id']}}">{{$AccidentInfo['shipName_Cn']}} | {{$AccidentInfo['shipName_En']}}</td>
                                <td class="center">{{$AccidentInfo['Voy_No']}} | {{$AccidentInfo['CP_No']}}</td>
                                <td class="center">{{convert_date($AccidentInfo['AccidentDate'])}}</td>
                                <td class="center">{{$AccidentInfo['Port_Cn']}}</td>
                                <td class="center">{{$AccidentInfo['Place']}}</td>
                                <td class="center"><label class="simple_text">{{$AccidentInfo['Content']}}</label></td>
                                <td class="center">
                                    <span class="badge badge-{{ g_enum('AccidentTypeData')[$AccidentInfo['AccidentKind']][1] }}">{{ g_enum('AccidentTypeData')[$AccidentInfo['AccidentKind']][0] }}</span>  
                                </td>
                                <td class="center">
                                    @if(!empty($AccidentInfo['AddFileName']))
                                        <a href="/fileDownload?type=repair&path={{$AccidentInfo['AddFileName']}}&filename={{$AccidentInfo['AddFileServerPath']}}" class="hide-option" title="{{$AccidentInfo['AddFileServerPath']}}">
                                            <i class="icon-file"></i>
                                        </a>
                                    @endif
                                </td>
                                @if(!$isHolder)
                                    <td class="action-buttons">
                                        <a href="shipAccidentDetail?id={{$AccidentInfo['id']}}&readonly=0" class="blur row_modify_btn"><i class="icon-edit bigger-130"></i></a>
                                        <a href="javascript:void(0);" class="red trash_btn"><i class="icon-trash bigger-130"></i></a>
                                    </td>
                                @endif
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                    {!! $AccidentInfos->render() !!}
                </div>
            </div>
        </div>

    </div>

    <script type="text/javascript">


        var token = '<?php echo csrf_token() ?>';

        $(function () {
            $('.search-btn').on('click', function () {
                var ship_id = $('#search_ship_id').val();
                var voy_number = $('#search_voy_number').val();
                var param = '';
                if (ship_id.length > 0)
                    param = '?ship=' + ship_id;
                if (voy_number.length > 0)
                    param = param.length == 0 ? '?voy=' + voy_number : (param + '&voy=' + voy_number);

                location.href = 'shipAccidentRegister' + param;
            });

            $('#search_ship_id').on('change', function () {
                var shipId = $('#search_ship_id').val();
                $.post('getVoyList', {_token: token, shipId: shipId}, function (data) {
                    $('#Voy').html(data);
                });
            });

            $('.trash_btn').on('click', function () {

                var obj = $(this).closest('tr').children();
                var shipName = obj.eq(0).html();
                var voyNo = obj.eq(1).html();
                var accidentId = obj.eq(0).data('id');

                bootbox.confirm("[" + shipName + "] 号 " + voyNo + "航次的事故记录真要删掉吗?", function(result) {
                    if(result) {
                        $.post('AccidentDelete', {'_token': token, 'accidentId': accidentId}, function(data){
                            var returnCode = parseInt(data);
                            if (returnCode > 0) {
                                window.location.reload();
                            } else if(returnCode == -1)
                                alert("错误, 在数据库无法找到事故记录。");
                            else if(returnCode == -2)
                                alert("错误, 没有对于事故记录的删掉权限。");
                            else if(returnCode == -2)
                                alert("错误, 无法删掉。");
                        });
                    }
                });

            });
        });

    </script>

@endsection

