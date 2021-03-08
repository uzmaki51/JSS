@extends('layout.header-print')

@section('content')
    {{--왼쪽의 부분메뉴에--}}
    <div class="main-content">
        <script src="/assets/js/fuelux/fuelux.spinner.min.js"></script>
        <style>
            tr[data-id="0"] td{
                height: 24px;
            }
            .spinner-input.form-control {
                line-height: 0px;
            }
        </style>
        <div class="col-md-12">
            <div class="space-10"></div>

            <div class="row">
                <div class="col-md-12">
                    <div class="col-md-12">
                        <table class="table table-bordered" id="ship_supplyplancollection_table">
                            <thead>
                            <tr>
                                <td class="center">년도</td>
                                <td class="center">배이름</td>
                                <td class="center">부문</td>
                                <td class="center">1월</td>
                                <td class="center">2월</td>
                                <td class="center">3월</td>
                                <td class="center">4월</td>
                                <td class="center">5월</td>
                                <td class="center">6월</td>
                                <td class="center">7월</td>
                                <td class="center">8월</td>
                                <td class="center">9월</td>
                                <td class="center">10월</td>
                                <td class="center">11월</td>
                                <td class="center">12월</td>
                                <td class="center">합계</td>
                            </tr>
                            </thead>
                            <tbody>
                            <tr>
                                <td class="center" rowspan="{{$nShips*($nDepts+1)}}">{{$yearCol}}</td>
                            <?php $shipIndex = 1;?>
                            @foreach($shipcolList as $ship)
                                <?php $shipRow = 0;?>
                                @if($shipIndex!=1)
                                    <tr>@endif
                                        <td class="center"
                                            rowspan="{{$nDepts+1}}">{{$ship['ShipName_En']}}</td>
                                    <?php $deptIndex = 1;?>
                                    @foreach($deptInfos as $deptInfo)
                                        <?php $shipRow = 1;?>
                                        @if($deptIndex!=1)
                                            <tr>@endif
                                                <td class="center">{{$deptInfo['Dept_Cn']}}</td>
                                                <td class="center">@if(isset($supplyAmounts[$yearCol.$ship['ShipName'].$deptInfo['id'].'1']))
                                                        {{$supplyAmounts[$yearCol.$ship['ShipName'].$deptInfo['id'].'1']}}@endif</td>
                                                <td class="center">@if(isset($supplyAmounts[$yearCol.$ship['ShipName'].$deptInfo['id'].'2']))
                                                        {{$supplyAmounts[$yearCol.$ship['ShipName'].$deptInfo['id'].'2']}}@endif</td>
                                                <td class="center">@if(isset($supplyAmounts[$yearCol.$ship['ShipName'].$deptInfo['id'].'3']))
                                                        {{$supplyAmounts[$yearCol.$ship['ShipName'].$deptInfo['id'].'3']}}@endif</td>
                                                <td class="center">@if(isset($supplyAmounts[$yearCol.$ship['ShipName'].$deptInfo['id'].'4']))
                                                        {{$supplyAmounts[$yearCol.$ship['ShipName'].$deptInfo['id'].'4']}}@endif</td>
                                                <td class="center">@if(isset($supplyAmounts[$yearCol.$ship['ShipName'].$deptInfo['id'].'5']))
                                                        {{$supplyAmounts[$yearCol.$ship['ShipName'].$deptInfo['id'].'5']}}@endif</td>
                                                <td class="center">@if(isset($supplyAmounts[$yearCol.$ship['ShipName'].$deptInfo['id'].'6']))
                                                        {{$supplyAmounts[$yearCol.$ship['ShipName'].$deptInfo['id'].'6']}}@endif</td>
                                                <td class="center">@if(isset($supplyAmounts[$yearCol.$ship['ShipName'].$deptInfo['id'].'7']))
                                                        {{$supplyAmounts[$yearCol.$ship['ShipName'].$deptInfo['id'].'7']}}@endif</td>
                                                <td class="center">@if(isset($supplyAmounts[$yearCol.$ship['ShipName'].$deptInfo['id'].'8']))
                                                        {{$supplyAmounts[$yearCol.$ship['ShipName'].$deptInfo['id'].'8']}}@endif</td>
                                                <td class="center">@if(isset($supplyAmounts[$yearCol.$ship['ShipName'].$deptInfo['id'].'9']))
                                                        {{$supplyAmounts[$yearCol.$ship['ShipName'].$deptInfo['id'].'9']}}@endif</td>
                                                <td class="center">@if(isset($supplyAmounts[$yearCol.$ship['ShipName'].$deptInfo['id'].'10']))
                                                        {{$supplyAmounts[$yearCol.$ship['ShipName'].$deptInfo['id'].'10']}}@endif</td>
                                                <td class="center">@if(isset($supplyAmounts[$yearCol.$ship['ShipName'].$deptInfo['id'].'11']))
                                                        {{$supplyAmounts[$yearCol.$ship['ShipName'].$deptInfo['id'].'11']}}@endif</td>
                                                <td class="center">@if(isset($supplyAmounts[$yearCol.$ship['ShipName'].$deptInfo['id'].'12']))
                                                        {{$supplyAmounts[$yearCol.$ship['ShipName'].$deptInfo['id'].'12']}}@endif</td>
                                                <td class="center">@if(isset($supplyAmounts[$yearCol.$ship['ShipName'].$deptInfo['id'].'0']))
                                                        {{$supplyAmounts[$yearCol.$ship['ShipName'].$deptInfo['id'].'0']}}@endif</td>
                                            </tr>

                                            <?php $deptIndex++?>
                                            @endforeach
                                            <tr>
                                                <td class="center">합계</td>
                                                <td class="center">@if(isset($supplyAmounts[$yearCol.'1'.$ship['ShipName']]))
                                                        {{$supplyAmounts[$yearCol.'1'.$ship['ShipName']]}}@endif</td>
                                                <td class="center">@if(isset($supplyAmounts[$yearCol.'2'.$ship['ShipName']]))
                                                        {{$supplyAmounts[$yearCol.'2'.$ship['ShipName']]}}@endif</td>
                                                <td class="center">@if(isset($supplyAmounts[$yearCol.'3'.$ship['ShipName']]))
                                                        {{$supplyAmounts[$yearCol.'3'.$ship['ShipName']]}}@endif</td>
                                                <td class="center">@if(isset($supplyAmounts[$yearCol.'4'.$ship['ShipName']]))
                                                        {{$supplyAmounts[$yearCol.'4'.$ship['ShipName']]}}@endif</td>
                                                <td class="center">@if(isset($supplyAmounts[$yearCol.'5'.$ship['ShipName']]))
                                                        {{$supplyAmounts[$yearCol.'5'.$ship['ShipName']]}}@endif</td>
                                                <td class="center">@if(isset($supplyAmounts[$yearCol.'6'.$ship['ShipName']]))
                                                        {{$supplyAmounts[$yearCol.'6'.$ship['ShipName']]}}@endif</td>
                                                <td class="center">@if(isset($supplyAmounts[$yearCol.'7'.$ship['ShipName']]))
                                                        {{$supplyAmounts[$yearCol.'7'.$ship['ShipName']]}}@endif</td>
                                                <td class="center">@if(isset($supplyAmounts[$yearCol.'8'.$ship['ShipName']]))
                                                        {{$supplyAmounts[$yearCol.'8'.$ship['ShipName']]}}@endif</td>
                                                <td class="center">@if(isset($supplyAmounts[$yearCol.'9'.$ship['ShipName']]))
                                                        {{$supplyAmounts[$yearCol.'9'.$ship['ShipName']]}}@endif</td>
                                                <td class="center">@if(isset($supplyAmounts[$yearCol.'10'.$ship['ShipName']]))
                                                        {{$supplyAmounts[$yearCol.'10'.$ship['ShipName']]}}@endif</td>
                                                <td class="center">@if(isset($supplyAmounts[$yearCol.'11'.$ship['ShipName']]))
                                                        {{$supplyAmounts[$yearCol.'11'.$ship['ShipName']]}}@endif</td>
                                                <td class="center">@if(isset($supplyAmounts[$yearCol.'12'.$ship['ShipName']]))
                                                        {{$supplyAmounts[$yearCol.'12'.$ship['ShipName']]}}@endif</td>
                                                <td class="center">@if(isset($supplyAmounts[$yearCol.'0'.$ship['ShipName']]))
                                                        {{$supplyAmounts[$yearCol.'0'.$ship['ShipName']]}}@endif</td>
                                            </tr>
                                            <?php $shipIndex++;?>
                                            <?php $shipRow = 0;?>
                                            @endforeach
                                            </tr>
                                            <tr>
                                                <td class="center" colspan="3">계</td>
                                                <td class="center">@if(isset($supplyAmounts[$yearCol.'1'.'0']))
                                                        {{$supplyAmounts[$yearCol.'1'.'0']}}@endif</td>
                                                <td class="center">@if(isset($supplyAmounts[$yearCol.'2'.'0']))
                                                        {{$supplyAmounts[$yearCol.'2'.'0']}}@endif</td>
                                                <td class="center">@if(isset($supplyAmounts[$yearCol.'3'.'0']))
                                                        {{$supplyAmounts[$yearCol.'3'.'0']}}@endif</td>
                                                <td class="center">@if(isset($supplyAmounts[$yearCol.'4'.'0']))
                                                        {{$supplyAmounts[$yearCol.'4'.'0']}}@endif</td>
                                                <td class="center">@if(isset($supplyAmounts[$yearCol.'5'.'0']))
                                                        {{$supplyAmounts[$yearCol.'5'.'0']}}@endif</td>
                                                <td class="center">@if(isset($supplyAmounts[$yearCol.'6'.'0']))
                                                        {{$supplyAmounts[$yearCol.'6'.'0']}}@endif</td>
                                                <td class="center">@if(isset($supplyAmounts[$yearCol.'7'.'0']))
                                                        {{$supplyAmounts[$yearCol.'7'.'0']}}@endif</td>
                                                <td class="center">@if(isset($supplyAmounts[$yearCol.'8'.'0']))
                                                        {{$supplyAmounts[$yearCol.'8'.'0']}}@endif</td>
                                                <td class="center">@if(isset($supplyAmounts[$yearCol.'9'.'0']))
                                                        {{$supplyAmounts[$yearCol.'9'.'0']}}@endif</td>
                                                <td class="center">@if(isset($supplyAmounts[$yearCol.'10'.'0']))
                                                        {{$supplyAmounts[$yearCol.'10'.'0']}}@endif</td>
                                                <td class="center">@if(isset($supplyAmounts[$yearCol.'11'.'0']))
                                                        {{$supplyAmounts[$yearCol.'11'.'0']}}@endif</td>
                                                <td class="center">@if(isset($supplyAmounts[$yearCol.'12'.'0']))
                                                        {{$supplyAmounts[$yearCol.'12'.'0']}}@endif</td>
                                                <td class="center">@if(isset($supplyAmounts[$yearCol.'0'.'0']))
                                                        {{$supplyAmounts[$yearCol.'0'.'0']}}@endif</td>
                                            </tr>

                            </tbody>
                        </table>
                    </div>

                </div>
            </div>

        </div>
    </div>
@endsection