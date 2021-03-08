@if($outMthd == 2)
    <?php $header = 'layout.excel-header'; ?>
@else
    <?php $header = 'layout.header-print'; ?>
@endif
@extends($header)
@section('content')
    @if($outMthd == 1)
        <div class="main-content">
            <div class="page-content">
                <div class="col-md-12">
                    <div class="row">
    @else
        @include('layout.excel-style')
    @endif
                    <table id="repair_info_table" class="table table-striped table-bordered table-hover">
                        <thead>
                        <tr>
                            <th class="center">배이름</th>
                            <th class="center">항차번호</th>
                            <th class="center">날자</th>
                            <th class="center">내용</th>
                            <th class="center">장소</th>
                            <th class="center">구분</th>
                            <th class="center">지도성원</th>
                            <th class="center">금액</th>
                        </tr>
                        </thead>
                        <tbody>

                        @foreach($RepairInfos as $RepairInfo)
                            <tr class="center">
                                <td class="center">{{$RepairInfo['shipName_Cn'].' | '.$RepairInfo['shipName_En']}}</td>
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
                                <td class="center">{{$RepairInfo['Amount']}}</td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
    @if($outMthd == 1)
                </div>
            </div>
        </div>
    </div>
    @endif
@endsection

