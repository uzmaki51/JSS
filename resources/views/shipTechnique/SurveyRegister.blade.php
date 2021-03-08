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
                        <b>배검사</b>
                        <small>
                            <i class="icon-double-angle-right"></i>
                            보고서등록
                        </small>
                    </h4>
                </div>
            </div>

            <div class="col-sm-12">
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
                    <div class="col-sm-5" style="text-align: right;float:right;">
                        <button class="btn btn-sm btn-primary no-radius search-btn" style="width: 80px">
                            <i class="icon-search"></i>
                            검색
                        </button>
                        @if(!$isHolder)
                            <a class="btn btn-sm btn-primary no-radius" href="shipSurveyDetail" style="width: 80px">
                                <i class="icon-plus-sign-alt"></i>
                                추가
                            </a>
                        @endif
                    </div>
                </div>
                <div class="row">
                    <div class="space-4"></div>
                    <table class="table table-striped table-bordered table-hover">
                        <thead>
                        <tr class="black br-hblue">
                            <th class="center">배이름</th>
                            <th class="center">항차번호</th>
                            <th class="center">검사날자</th>
                            <th class="center">장소</th>
                            <th class="center">종류</th>
                            <th class="center">검사내용</th>
                            <th class="center">검사비</th>
                            <th class="center">첨부파일</th>
                            @if(!$isHolder)
                                <th class="center" width="60px"></th>
                            @endif
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($SurveyInfos as $SurveyInfo)
                            <tr class="center">
                                <td class="center" data-id="{{$SurveyInfo['id']}}">{{$SurveyInfo['shipName_Cn']}} | {{$SurveyInfo['shipName_En']}}</td>
                                <td class="center">{{$SurveyInfo['Voy_No']}} | {{$SurveyInfo['CP_No']}}</td>
                                <td class="center">{{convert_date($SurveyInfo['SurveyDate'])}}</td>
                                <td class="center">{{$SurveyInfo['Port_Cn']}}</td>
                                <td class="center">{{$SurveyInfo['SurveyKind_Cn']}}</td>
                                <td><label class="simple_text">{{$SurveyInfo['Content']}}</label></td>
                                <td class="center">{{$SurveyInfo['Amount']}}</td>
                                <td class="center">
                                    @if(!empty($SurveyInfo['AddFileName']))
                                        <a href="/fileDownload?type=repair&path={{$SurveyInfo['AddFileName']}}&filename={{$SurveyInfo['AddFileServerPath']}}" class="hide-option" title="{{$SurveyInfo['AddFileServerPath']}}">
                                            <i class="icon-file"></i>
                                        </a>
                                    @endif
                                </td>
                                @if(!$isHolder)
                                    <td class="action-buttons">
                                        <a href="shipSurveyDetail?id={{$SurveyInfo['id']}}&readonly=0" class="blur row_modify_btn"><i class="icon-edit bigger-130"></i></a>
                                        <a href="javascript:void(0);" class="red trash_btn"><i class="icon-trash bigger-130"></i></a>
                                    </td>
                                @endif
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                    {!! $SurveyInfos->render() !!}
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

                location.href = 'shipSurveyRegister' + param;
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
                var surveyId = obj.eq(0).data('id');

                bootbox.confirm("[" + shipName + "] 호 " + voyNo + "항차 검사기록에 대한 삭제를 진행하겠습니까?", function(result) {
                    if(result) {
                        $.post('SurveyDelete', {'_token': token, 'surveyId': surveyId}, function(data){
                            var returnCode = parseInt(data);
                            if (returnCode > 0) {
                                window.location.reload();
                            } else if(returnCode == -1)
                                alert("오유, 자료기지에서 검사기록을 찾을수 없습니다.");
                            else if(returnCode == -2)
                                alert("오유, 검사기록에 대한 삭제권환이 없습니다.");
                            else if(returnCode == -2)
                                alert("오유, 삭제할수 없습니다.");
                        });
                    }
                });

            });

        });

    </script>

@endsection

