@if ($outMthd == 1)
    <?php $header = "layout.header-print";?>
@else
    <?php $header = "layout.excel-header";?>
@endif

@extends($header)
@section('content')
    @if($outMthd == 1)
    <div class="main-content">
        <div class="page-content">

            <div class="col-md-12">
                <div class="row">
                    <div class="space-4"></div>
    @else
        @include('layout.excel-style')
    @endif
                    <table class="table table-striped table-bordered table-hover">
                        <thead>
                        <tr>
                            <th class="center" >船舶名称</th>
                            <th class="center">航次号码</th>
                            <th class="center">日期</th>
                            <th class="center">港口名称</th>
                            <th class="center">地点</th>
                            <th class="center">内容</th>
                            <th class="center">种类</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($AccidentInfos as $AccidentInfo)
                            <tr class="center">
                                <td class="center">{{$AccidentInfo['shipName_Cn']}} | {{$AccidentInfo['shipName_En']}}</td>
                                <td class="center">{{$AccidentInfo['Voy_No']}} | {{$AccidentInfo['CP_No']}}</td>
                                <td class="center">{{convert_date($AccidentInfo['AccidentDate'])}}</td>
                                <td class="center">{{$AccidentInfo['Port_Cn']}}</td>
                                <td class="center">{{$AccidentInfo['Place']}}</td>
                                <td class="center"><label class="simple_text">{{$AccidentInfo['Content']}}</label></td>
                                <td class="center">
                                    @if($AccidentInfo['AccidentKind'] == 1)
                                        搁浅
                                    @elseif($AccidentInfo['AccidentKind'] == 2)
                                        冲突
                                    @elseif($AccidentInfo['AccidentKind'] == 3)
                                        故障破损
                                    @elseif($AccidentInfo['AccidentKind'] == 4)
                                        丢失
                                    @elseif($AccidentInfo['AccidentKind'] == 5)
                                        货物不足
                                    @endif
                                </td>
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

