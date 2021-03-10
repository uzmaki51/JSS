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
                            <th class="center">船舶名称</th>
                            <th class="center">航次号码</th>
                            <th class="center">日期</th>
                            <th class="center">内容</th>
                            <th class="center">地点</th>
                            <th class="center">区分</th>
                            <th class="center">领导</th>
                            <th class="center">金额</th>
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
                                        定期
                                    @elseif($RepairInfo['RepairKind'] == 2)
                                        突发
                                    @elseif($RepairInfo['RepairKind'] == 3)
                                        自己
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

