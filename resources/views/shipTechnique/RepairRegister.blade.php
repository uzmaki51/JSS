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
                        <b>배수리</b>
                        <small>
                            <i class="icon-double-angle-right"></i>
                            보고서등록
                        </small>
                    </h4>
                </div>
            </div>

            <div class="col-md-12">
                <div class="row">
                    <div class="col-sm-3">
                        <label style="float:left;padding-top:5px">배이름 :</label>
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
                        <label style="float:left;padding-top:5px">항차번호:</label>
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
                    <div class="col-sm-2" style="text-align: right; float: right;">
                        <button class="btn btn-sm btn-primary no-radius search-btn" style="width: 80px">
                            <i class="icon-search"></i>
                            검색
                        </button>
                        @if(!$isHolder)
                            <a class="btn btn-sm btn-primary no-radius" href="shipRepairDetail" style="width: 80px"><i class="icon-plus-sign-alt"></i>추가</a>
                        @endif
                    </div>
                </div>
                <div class="row">
                    <div class="space-4"></div>
                    <table id="repair_info_table" class="table table-striped table-bordered table-hover">
                        <thead>
                        <tr class="black br-hblue">
                            <th class="center">배이름</th>
                            <th class="center">항차번호</th>
                            <th class="center">날자</th>
                            <th class="center">내용</th>
                            <th class="center">장소</th>
                            <th class="center">구분</th>
                            <th class="center">지도성원</th>
                            <th class="center">금액</th>
                            <th class="center">첨부파일</th>
                            @if(!$isHolder)
                                <th class="center" width="70px"></th>
                            @endif
                        </tr>
                        </thead>
                        <tbody>

                        @foreach($RepairInfos as $RepairInfo)
                            <tr class="center">
                                <td class="center" data-id="{{$RepairInfo['id']}}">{{$RepairInfo['shipName_Cn'].' | '.$RepairInfo['shipName_En']}}</td>
                                <td class="center">{{$RepairInfo['Voy_No']}} | {{$RepairInfo['CP_No']}}</td>
                                <td class="center">{{convert_date($RepairInfo['FromDate'])}}~{{convert_date($RepairInfo['ToDate'])}}</td>
                                <td><lable class="simple_text">{{$RepairInfo['Content']}}</lable></td>
                                <td class="center">{{$RepairInfo['Place']}}</td>
                                <td class="center">
                                    @if($RepairInfo['RepairKind'] == 1)
                                        정기
                                    @elseif($RepairInfo['RepairKind'] == 2)
                                        돌발
                                    @elseif($RepairInfo['RepairKind'] == 3)
                                        자체
                                    @endif
                                </td>
                                <td class="center">{{$RepairInfo['D_Officer']}}</td>
                                <td class="center">{{App\Http\Controllers\Util::getNumberFt($RepairInfo['Amount'])}}</td>
                                <td class="center">
                                    @if(!empty($RepairInfo['AddFileName']))
                                        <a href="/fileDownload?type=repair&path={{$RepairInfo['AddFileName']}}&filename={{$RepairInfo['AddFileServerPath']}}" class="hide-option" title="{{$RepairInfo['AddFileServerPath']}}">
                                            <i class="icon-file"></i>
                                        </a>
                                    @endif
                                </td>
                                @if(!$isHolder)
                                    <td class="action-buttons">
                                        <a href="shipRepairDetail?id={{$RepairInfo['id']}}&readonly=0" class="blur row_modify_btn"><i class="icon-edit bigger-130"></i></a>
                                        <a href="javascript:void(0);" class="red trash_btn"><i class="icon-trash bigger-130"></i></a>
                                    </td>
                                @endif
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                    {{--{!! $RepairInfos->render() !!}--}}
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
                if(ship_id.length > 0)
                    param = '?ship=' + ship_id;
                if(voy_number.length > 0)
                    param = param.length == 0 ? '?voy=' + voy_number : (param + '&voy=' + voy_number);

                location.href = 'shipRepairRegister' + param;
            });

            $('#search_ship_id').on('change', function () {
                var shipId = $('#search_ship_id').val();
                $.post('getVoyList', {_token:token,shipId:shipId}, function(data){
                    $('#Voy').html(data);
                });
            });

            $('.trash_btn').on('click', function () {

                var obj = $(this).closest('tr').children();
                var shipName = obj.eq(0).html();
                var voyNo = obj.eq(1).html();
                var repairId = obj.eq(0).data('id');
                bootbox.confirm("[" + shipName + "] 호 " + voyNo + "항차 수리기록에 대한 삭제를 진행하겠습니까?", function(result) {
                    if(result) {
                        $.post('RepairDelete', {'_token': token, 'repairId': repairId}, function(data){
                            var returnCode = parseInt(data);
                            if (returnCode > 0) {
                                window.location.reload();
                            } else if(returnCode == -1)
                                alert("오유, 자료기지에서 수리기록을 찾을수 없습니다.");
                            else if(returnCode == -2)
                                alert("오유, 수리기록에 대한 삭제권환이 없습니다.");
                            else if(returnCode == -2)
                                alert("오유, 삭제할수 없습니다.");
                        });
                    }
                });
            });
        });

    </script>

@endsection

